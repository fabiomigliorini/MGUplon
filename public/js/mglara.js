function formataCodigo(numero) {
    if (numero > 99999999) {
        return numero;
    }

    numero = new String("00000000" + numero);
    numero = numero.substring(numero.length-8, numero.length);
    return numero;
}

function formataCnpjCpf(numero) {
    //CNPJ
    if (numero > 99999999999) {
        numero = new String("00000000000000" + numero);
        numero = numero.substring(numero.length-14, numero.length);
        // 01 234 567 8901 23
        // 04.576.775/0001-60
        numero = numero.substring(0, 2) 
                 + "."
                 + numero.substring(2, 5)
                 + "."
                 + numero.substring(5, 8)
                 + "/"
                 + numero.substring(8, 12)
                 + "-"
                 + numero.substring(12, 14)
                 ;
    } else { //CPF
        numero = "000000000000" + numero;
        numero = numero.substring(numero.length-11, numero.length);
        // 012 345 678 90
        // 123 456 789 01
        // 803.452.710.68
        numero = numero.substring(0, 3) 
                 + "."
                 + numero.substring(3, 6)
                 + "."
                 + numero.substring(6, 9)
                 + "-"
                 + numero.substring(9, 11)
                 ;
    }

    return numero;
}

function recarregaDiv(div, url) {
    if(url === undefined) {
        url = $(location).attr('href');
    };

    if (url.indexOf("?") == -1)
        url += '?';
    else
        url += '&';
    
    url += '_div=' + div + ' #' + div + ' > *';

    $('#' + div).load(url, function (){
        inicializa('#' + div + ' *');
    });
}

function recarregaDivS(divs, url) {
    if (url === undefined) {
        url = $(location).attr('href');
    };
    
    if (!$.isArray(divs)) {
        divs = [divs];
        
        if (url.indexOf("?") == -1) {
            url += '?';
        } else {
            url += '&';
        }

        url += '_div=' + divs + ' #' + divs + ' > *';
    }

    $.get(url).done(function (html) {
        var newDom = $(html);
        $.each(divs, function (i, div) {
            $('#'+div).replaceWith($('#'+div, newDom));
            inicializa('#' + div + ' *');
        });
    });
}


function excluirClick(tag) {
    var url = $(tag).attr('href');
    var pergunta = $(tag).data('pergunta');
    var funcaoAfterDelete = $(tag).data('after-delete');
    var funcaoOnError = $(tag).data('on-error');
    
    pergunta = (typeof pergunta === 'undefined') ? 'Tem certeza que deseja excluir o registro?' : pergunta;
    
    swal({
      title: pergunta,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'POST',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                dataType: 'json',
                success: function(retorno) {
                    if (retorno.resultado) {
                        var mensagem = retorno.mensagem;
                        var descricao = '';
                        var tipo = 'success';
                        var funcaoExecutar = funcaoAfterDelete;
                    } else {
                        var mensagem = retorno.mensagem;
                        var descricao = '';
                        //descricao += "<hr><code>";
                        descricao += JSON.stringify(retorno, undefined, 2);
                        //descricao += "</code>";
                        var funcaoExecutar = funcaoOnError;
                        var tipo = 'error';
                    }

                    swal(mensagem, descricao, tipo);
                    if (typeof funcaoExecutar !== 'undefined'){
                        eval(funcaoExecutar);
                    }
                    
                    console.log(retorno);
                    
                },
                error: function (XHR, textStatus) {
                    if(XHR.status === 200) {
                        swal('Você não tem acesso a esse recurso!', '', 'error'); 
                    } 
                }
            });        
        } 
    });     

    return true;
}


function inativarClick(tag) {
    
    var url         = $(tag).attr('href');
    var pergunta    = $(tag).data('pergunta');
    var acao        = $(tag).data('acao');
    var codigo      = $(tag).data('codigo');
    var funcaoAfterInativar = $(tag).data('after-inativar');
    var funcaoOnError = $(tag).data('on-error');
    
    pergunta = (typeof pergunta === 'undefined') ? 'Tem certeza que deseja inativar o registro?' : pergunta;
    
    swal({
      title: pergunta,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'PUT',
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                dataType: 'json',
                success: function(retorno) {
                    
                    if (retorno.resultado) {
                        var mensagem = retorno.mensagem;
                        var descricao = '';
                        var tipo = 'success';
                        var funcaoExecutar = funcaoAfterInativar;
                    } else {
                        var mensagem = retorno.mensagem;
                        var descricao = '';
                        //mensagem += '<hr><pre>';
                        descricao += JSON.stringify(retorno, undefined, 2);
                        //mensagem += '</pre>';
                        var funcaoExecutar = funcaoOnError;
                        var tipo = 'error';
                    }
                    
                    swal(mensagem, descricao, tipo);
                    if (typeof funcaoExecutar !== 'undefined') {
                        eval(funcaoExecutar);
                    }
                    
                    console.log(retorno);
                    
                },
                error: function (XHR, textStatus) {
                    if(XHR.status === 200) {
                        swal('Você não tem acesso a esse recurso!', '', 'error'); 
                    } 
                }
            });
        }
        
    });
    
    return true;
}


function inicializa(elemento) {
    $(elemento).find('a[data-excluir]').click(function(event) {
        event.preventDefault();
        return excluirClick($(this));
    });
    $(elemento).find('a[data-inativar]').click(function(event) {
        event.preventDefault();
        return inativarClick($(this));
    });
}

$(document).ready(function() {
    inicializa('*');
});  



