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

function excluirAtivarInativar (type, method, url, pergunta, funcaoAfter, funcaoOnError) {
 
    // Executa Pergunta
    swal({
      title: pergunta,
      type: "warning",
      showCancelButton: true,
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
        
        // Se confirmou
        if (isConfirm) {
            
            //Faz chamada Ajax
            $.ajax({
                type: type,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                dataType: 'json',
                
                // Caso veio retorno
                success: function(retorno) {
                    
                    // Se executou
                    if (retorno.OK) {
                        swal({
                            title: 'Excluido!',
                            text: 'Excluido com sucesso!',
                            type: 'success',
                        }, function () {
                            if (typeof funcaoAfter !== 'undefined') {
                                eval(funcaoAfter);
                            }
                        });
                        
                    // Se não executou
                    } else {
                        swal({
                            title: 'Erro ao excluir!',
                            text: retorno.mensagem,
                            type: 'error',
                        }, function () {
                            if (typeof funcaoOnError !== 'undefined') {
                                eval(funcaoOnError);
                            }
                        });
                    }
                    
                },
                
                // Caso Erro
                error: function (XHR) {
                    
                    if(XHR.status === 403) {
                        var title = 'Permissão Negada!';
                    } else {
                       var title = 'Falha na execução!';
                    }
                    
                    swal({
                        title: title,
                        text: XHR.status + ' ' + XHR.statusText,
                        type: 'error',
                    }, function () {
                        if (typeof funcaoOnError !== 'undefined') {
                            eval(funcaoOnError);
                        }
                    });
                }
            });        
        } 
    });     
    
    return true;
}


function excluirClick(tag) {
    
    var url = $(tag).attr('href');
    var pergunta = $(tag).data('pergunta');
    var funcaoAfter = $(tag).data('after');
    var funcaoOnError = $(tag).data('on-error');
    
    pergunta = (typeof pergunta === 'undefined') ? 'Tem certeza?' : pergunta;

    return excluirAtivarInativar ('POST', 'DELETE', url, pergunta, funcaoAfter, funcaoOnError)
}


function inativarClick(tag) {
    
    var url = $(tag).attr('href');
    var pergunta = $(tag).data('pergunta');
    var funcaoAfter = $(tag).data('after');
    var funcaoOnError = $(tag).data('on-error');
    
    pergunta = (typeof pergunta === 'undefined') ? 'Tem certeza?' : pergunta;
    
    return excluirAtivarInativar ('PUT', 'PUT', url, pergunta, funcaoAfter, funcaoOnError)
    
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



