<?php

namespace MGLara\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * Description of UnidadeMedidaRepository
 * 
 * @property Validator $validator
 * @property UnidadeMedida $model
 */
class GeradorCodigoRepository {
    
    public function caminhoModel ($arquivo = '') {
        if (!empty($arquivo)) {
            if (!strstr(strtolower($arquivo), '.php')) {
                $arquivo .= '.php';
            }
        }
        return app_path() . '/Models/' . $arquivo;
    }
    
    public function caminhoRepository ($arquivo = '') {
        if (!empty($arquivo)) {
            if (!strstr(strtolower($arquivo), 'Repository.php')) {
                $arquivo .= 'Repository.php';
            }
        }
        return app_path() . '/Repositories/' . $arquivo;
    }
    
    public function caminhoPolicy ($arquivo = '') {
        if (!empty($arquivo)) {
            if (!strstr(strtolower($arquivo), 'Policy.php')) {
                $arquivo .= 'Policy.php';
            }
        }
        return app_path() . '/Policies/' . $arquivo;
    }
    
    public function buscaTabelas() {
        return DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'mgsis\' ORDER BY table_schema,table_name;');
    }
    
    public function buscaArquivoModel($tabela) {
        $path = $this->caminhoModel();
        $files = glob("$path*");
        $model = str_replace('tbl', '', $tabela);
        $arquivos =  preg_grep("/\/$model\.php$/i", $files);
        foreach ($arquivos as $arquivo) {
            return str_replace('.php', '', str_replace($path, '', $arquivo));
        }
        return ucfirst(strtolower($model));
    }
    
    public function buscaCamposTabela($tabela) {
        
        $sql = "
            SELECT 
                    column_name
                  , data_type
                  , udt_name
                  , character_maximum_length
                  , numeric_precision
                  , numeric_scale
                  , is_nullable
                  , column_default
            FROM information_schema.columns
            WHERE table_name = '{$tabela}'
            --ORDER BY column_name
        ";
        
        $cols = DB::select($sql);
        
        foreach ($cols as $col) {
            $tipo = trim($col->data_type);

            if (strstr($tipo, ' ')) {
                $tipo = $col->udt_name;
            }

            switch ($tipo)
            {
                case 'numeric':
                        $tipo .= "({$col->numeric_precision},{$col->numeric_scale})";
                        break;
                case 'varchar':
                        $tipo .= "({$col->character_maximum_length})";
                        break;
            }

            $col->tipo = $tipo;
            

            $comentario = ($col->is_nullable == 'NO')?'NOT NULL':'';
            if (!empty($col->column_default)) {
                $comentario .= ' DEFAULT ' . $col->column_default;
            }
            $col->comentario = trim($comentario);
            
        }
        
        return $cols;
    }
    
    public function buscaChavesEstrangeiras ($tabela) {

        $sql = "
            SELECT
                  ccu.table_name AS foreign_table_name
                , ccu.column_name AS foreign_column_name 
                , kcu.column_name 
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY' 
            AND tc.table_name='{$tabela}';
        ";
            
        $pais = DB::select($sql);

        // Busca nome dos arquivos de model
        foreach ($pais as $pai) {
            $pai->model = $this->buscaArquivoModel($pai->foreign_table_name);
            
            switch($pai->column_name) {
                
                case 'codusuariocriacao':
                    $pai->propriedade = 'UsuarioCriacao';
                    break;
                
                case 'codusuarioalteracao':
                    $pai->propriedade = 'UsuarioAlteracao';
                    break;
                
                default:
                    $pai->propriedade = $pai->model;
                    break;
            }

        }

        return $pais;
    }
    
    public function buscaChavesEstrangeirasFilhas($tabela) {
        $sql = "
            SELECT
                  tc.table_name AS foreign_table_name
                , kcu.column_name AS foreign_column_name 
                , ccu.column_name 
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY' 
            AND ccu.table_name='{$tabela}';
        ";
            
        $filhas = DB::select($sql);

        // Busca nome dos arquivos de model
        foreach ($filhas as $filha) {
            $filha->model = $this->buscaArquivoModel($filha->foreign_table_name);
        }

        return $filhas;
    }
    
    public function geraModel($tabela, $model, $titulo) {
        
        $cols = $this->buscaCamposTabela($tabela);
        $pais = $this->buscaChavesEstrangeiras($tabela);
        $filhas = $this->buscaChavesEstrangeirasFilhas($tabela);
        
        return view('gerador-codigo.arquivos.model', compact('tabela', 'model', 'titulo', 'cols', 'pais', 'filhas'))->render();
                
    }
    
    public function montaValidacoes($cols, $instancia_model) {
        $validacoes = [];
        foreach ($cols as $col) {
            if ($col->column_name == $instancia_model->getKeyName()) {
                continue;
            }
            switch ($col->udt_name) {
                case 'varchar':
                    $validacoes[$col->column_name]['size'] = [
                        'rule' => "size:$col->character_maximum_length",
                        'mensagem' => "O campo \"$col->column_name\" não pode conter mais que $col->character_maximum_length caracteres!",
                    ];
                    break;
                
                case 'date';
                case 'timestamp';
                    $validacoes[$col->column_name]['date'] = [
                        'rule' => 'date',
                        'mensagem' => "O campo \"$col->column_name\" deve ser uma data!",
                    ];
                    break;
                    
                case 'bool';
                    $validacoes[$col->column_name]['boolean'] = [
                        'rule' => 'boolean',
                        'mensagem' => "O campo \"$col->column_name\" deve ser um verdadeiro/falso (booleano)!",
                    ];
                    break;
                
                case 'numeric';
                    $validacoes[$col->column_name]['digits'] = [
                        'rule' => 'digits',
                        'mensagem' => "O campo \"$col->column_name\" deve conter no máximo $col->numeric_scale dígitos!",
                    ];
                case 'int8';
                case 'int4';
                case 'int2';
                    $validacoes[$col->column_name]['numeric'] = [
                        'rule' => 'numeric',
                        'mensagem' => "O campo \"$col->column_name\" deve ser um número!",
                    ];
                    break;
                
                default:
                    dd($col);
            }
            if ($col->is_nullable == 'NO') {
                $validacoes[$col->column_name]['required'] = [
                    'rule' => 'required',
                    'mensagem' => "O campo \"$col->column_name\" deve ser preenchido!",
                ];
            }
        }
        
        return $validacoes;
    }
    
    public function geraRepository($tabela, $model, $titulo) {
        
        $cols = $this->buscaCamposTabela($tabela);
        $filhas = $this->buscaChavesEstrangeirasFilhas($tabela);
        
        $instancia_model = "\\MGLara\\Models\\{$model}";
        $instancia_model = new $instancia_model();
        
        $validacoes = $this->montaValidacoes($cols, $instancia_model);
        
        return view('gerador-codigo.arquivos.repository', compact('tabela', 'model', 'titulo', 'cols', 'filhas', 'instancia_model', 'validacoes'))->render();
        
    }    
    
    public function geraPolicy($model) {
        return view('gerador-codigo.arquivos.policy', compact('model'))->render();
    }    
    
    public function salvaArquivo($arquivo, $conteudo) {
        
        if (file_exists($arquivo)) {
            $i = 1;
            while (file_exists("{$arquivo}.old.{$i}")) {
                $i++;
            }
            rename($arquivo, "{$arquivo}.old.{$i}");
        }
        if (file_put_contents($arquivo, $conteudo)) {
            chmod($arquivo, 0666);
            return true;
        }
        return false;
        
    }
    
    public function salvaModel($tabela, $model, $titulo) {
        $conteudo = $this->geraModel($tabela, $model, $titulo);
        $arquivo = $this->caminhoModel($model);
        return ($this->salvaArquivo($arquivo, $conteudo));
    }
    
    public function salvaRepository($tabela, $model, $titulo) {
        $conteudo = $this->geraRepository($tabela, $model, $titulo);
        $arquivo = $this->caminhoRepository($model);
        return ($this->salvaArquivo($arquivo, $conteudo));
    }
    
    public function salvaPolicy($model) {
        $conteudo = $this->geraPolicy($model);
        $arquivo = $this->caminhoPolicy($model);
        return ($this->salvaArquivo($arquivo, $conteudo));
    }
    
    public function stringRegistroPolicy($model) {
        return "\\MGLara\\Models\\{$model}::class => \\MGLara\\Policies\\{$model}Policy::class";
    }
    
    public function verificaRegistroPolicy($model) {
        $file = file_get_contents(app_path() . '/Providers/AuthServiceProvider.php');
        $string = $this->stringRegistroPolicy($model);
        return strstr($file, $string);
    }
    
}
