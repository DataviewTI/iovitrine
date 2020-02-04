new IOService(
  {
    name: 'Vitrine',
    dfId: 'default-form',
    wz: $('#default-wizard').wizard()
  },
  self => {
    setTimeout(() => {
      self.tabs['formacao-academica'].tab.addClass('disabled');

      // $("#user_name_container").style({ display: 'hidden' })
      document.getElementById('user_name').firstChild.nodeValue = '';

      console.log();

      self.tabs['cadastrar'].tab.on('shown.bs.tab', e => {
        IO.active = self;
      });

      self.tabs['listar'].tab.on('shown.bs.tab', e => {
        IO.active = self;
        self.dt.ajax.reload();
        self.dt.columns.adjust();
      });

      self.tabs['telefones-e-endereco'].tab.on('shown.bs.tab', e => {
        $('#celular1').focus();
      });

      self.tabs['resumo'].tab.on('shown.bs.tab', e => {
        $('#resumo').focus();
      });

      self.tabs['cadastrar'].tab.tab('show');
    });

    self.dt = $('#default-table')
      .DataTable({
        ajax: self.path + '/list',
        initComplete: function() {
          //parent call
          let api = this.api();
          // this.teste = 10;
          $.fn.dataTable.defaults.initComplete(this);

          api.addDTSelectFilter([
            // { el: $('#ft_loja'), column: 'otica' },
            { el: $('#ft_status'), column: 'groups' }
          ]);

          api.addDTInputFilter([
            { el: $('#ft_instituicao'), column: 'instituicao' },
            { el: $('#ft_curso'), column: 'curso' }
          ]);

          // $('#ft_dtini')
          //   .pickadate()
          //   .pickadate('picker')
          //   .on('render', function() {
          //     api.draw();
          //   });

          // $('#ft_dtfim')
          //   .pickadate()
          //   .pickadate('picker')
          //   .on('render', function() {
          //     api.draw();
          //   });

          // api.addDTBetweenDatesFilter({
          //   column: 'created_at',
          //   min: $('#ft_dtini'),
          //   max: $('#ft_dtfim')
          // });
        },
        footerCallback: function(row, data, start, end, display) {},
        columns: [
          { data: 'id', name: 'id' },
          { data: 'formacao' },
          { data: 'instituicao', name: 'instituicao' },
          { data: 'curso', name: 'curso' },
          { data: 'conclusao', name: 'data' },
          { data: 'nome' },
          { data: 'cpf' },
          { data: 'email', name: 'email' },
          { data: 'celular1', name: 'celular1' },
          { data: 'actions', name: 'actions' }
        ],
        columnDefs: [
          { targets: '__dt_', width: '3%', searchable: true, orderable: true },
          {
            targets: '__dt_formacao',
            searchable: false,
            orderable: true,
            width: '5%',
            render: function(data, type, row) {
              return row.formacao.length ? row.formacao[0].category : '';
            }
          },
          {
            targets: '__dt_instituicao',
            searchable: true,
            orderable: true,
            width: '10%',
            render: function(data, type, row) {
              return row.formacao.length ? row.formacao[0].instituicao : '';
            }
          },
          {
            targets: '__dt_curso',
            searchable: true,
            orderable: true,
            width: '15%',
            render: function(data, type, row) {
              return row.formacao.length ? row.formacao[0].curso : '';
            }
          },
          {
            targets: '__dt_data',
            width: '3%',
            render: function(data, type, row) {
              return row.formacao.length ? row.formacao[0].fim : '';
            }
          },
          {
            targets: '__dt_nome',
            searchable: true,
            orderable: true,
            width: 'auto'
          },
          {
            targets: '__dt_cpf',
            searchable: true,
            orderable: true,
            width: '7%'
          },
          {
            targets: '__dt_email',
            searchable: true,
            orderable: true,
            width: '15%'
          },
          {
            targets: '__dt_celular',
            searchable: true,
            orderable: true,
            width: '10%'
          },
          {
            targets: '__dt_acoes',
            width: '5%',
            className: 'text-center',
            searchable: false,
            orderable: false,
            render: function(data, type, row, y) {
              return self.dt.addDTButtons({
                buttons: [
                  { ico: 'ico-edit', _class: 'text-info', title: 'editar' },
                  { ico: 'ico-trash', _class: 'text-danger', title: 'excluir' }
                ]
              });
            }
          }
        ]
      })
      .on('click', '.btn-dt-button[data-original-title=editar]', function() {
        var data = self.dt.row($(this).parents('tr')).data();
        self.view(data.id);
      })
      .on('click', '.ico-trash', function() {
        var data = self.dt.row($(this).parents('tr')).data();
        self.delete(data.id);
      })
      // .on('click', '.ico-eye', function () {
      //   var data = self.dt.row($(this).parents('tr')).data();
      //   preview({ id: data.id });
      // })
      .on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
      });

    $('#cpf')
      .removeAttr('readonly')
      .mask('000.000.000-00', {
        onComplete: function(val, e, field) {
          self.fv[0].revalidateField('cpf').then(ret => {
            if (ret === 'Valid') $('#nome').focus();
          });
        }
      });

    $('#dt_nascimento')
      .pickadate({
        selectYears: 99,
        formatSubmit: 'yyyy-mm-dd 00:00:00',
        max: 'today'
      })
      .pickadate('picker')
      .on('render', function() {
        self.fv[0].revalidateField('dt_nascimento');
      });

    $('#telefone1, #telefone2, #celular1, #celular2').mask(
      $.jMaskGlobals.SPMaskBehavior,
      {
        onKeyPress: function(val, e, field, options) {
          self.fv[0].revalidateField($(field).attr('id'));
          field.mask(
            $.jMaskGlobals.SPMaskBehavior.apply({}, arguments),
            options
          );
        },
        onComplete: function(val, e, field) {
          $(field)
            .parent()
            .parent()
            .next()
            .find('input')
            .first()
            .focus();
        }
      }
    );

    $('#zipCode').mask('00000-000');

    let form = document.getElementById(self.dfId);

    let fv1 = FormValidation.formValidation(
      form.querySelector('.step-pane[data-step="1"]'),
      {
        fields: {
          cpf: {
            validators: {
              notEmpty: {
                message: 'O cpf é obrigatório'
              },
              id: {
                country: 'BR',
                message: 'cpf inválido'
              }
            }
          },
          nome: {
            validators: {
              notEmpty: {
                message: 'O nome completo é obrigatório!'
              },
              stringLength: {
                min: 5,
                message: 'Mínimo de 5 caracteres'
              }
            }
          },
          dt_nascimento: {
            validators: {
              date: {
                format: 'DD/MM/YYYY',
                message: 'Informe uma data válida!'
              }
            }
          },
          zipCode: {
            validators: {
              promise: {
                notEmpty: {
                  message: 'o cep é obrigatório'
                },
                enabled: true,
                promise: function(input) {
                  return getCep(input.value);
                }
              }
            }
          },
          address: {
            validators: {
              notEmpty: {
                message: 'O endereço é obrigatório'
              }
            }
          },
          address2: {
            validators: {
              notEmpty: {
                message: 'O bairro é obrigatório'
              }
            }
          },
          celular1: {
            validators: {
              phone: {
                country: 'BR',
                message: 'Celular inválido'
              },
              notEmpty: {
                message: 'O telefone Celular é obrigatório'
              }
            }
          },
          celular2: {
            validators: {
              phone: {
                country: 'BR',
                message: 'Celular inválido'
              }
            }
          },
          telefone1: {
            validators: {
              phone: {
                country: 'BR',
                message: 'Telefone inválido'
              }
            }
          },
          telefone2: {
            validators: {
              phone: {
                country: 'BR',
                message: 'Telefone inválido'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'O e-mail principal é obrigatório!'
              },
              emailAddress: {
                message: 'E-mail Inválido'
              }
            }
          },
          resumo: {
            validators: {
              notEmpty: {
                message: 'O Resumo é obrigatório!'
              }
            },
            callback: function(a, b) {
              return {
                valid: true, // or false
                message: 'The error message'
              };
            }
          } // has_images: {
          //   validators: {
          //     callback: {
          //       message: 'Insira a logo da empresa!',
          //       callback: function(input) {
          //         if (self.dz.files.length == 0) {
          //           toastr['error']('Insira a logo da empresa!');
          //           return false;
          //         }
          //         $('#has_images').val(true);
          //         return true;
          //       }
          //     }
          //   }
          // }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          submitButton: new FormValidation.plugins.SubmitButton(),
          bootstrap: new FormValidation.plugins.Bootstrap(),
          icon: new FormValidation.plugins.Icon({
            valid: 'fv-ico ico-check',
            invalid: 'fv-ico ico-close',
            validating: 'fv-ico ico-gear ico-spin'
          })
        }
      }
    )
      .setLocale('pt_BR', FormValidation.locales.pt_BR)
      .on('core.validator.validated', function(e) {
        if (e.field === 'zipCode' && e.validator === 'promise') {
          if (e.result.meta.data !== null) setCEP(e.result.meta.data, self);
          else {
          }
        }
      })
      .on('core.form.invalid', function(e) {
        let els = Object.values(form.elements);

        const x = els.filter(el => {
          return (
            el.classList.contains('is-invalid') &&
            el.getAttribute('tab') !== null
          );
        });

        if (x.length) {
          self.tabs[x[0].getAttribute('tab')].tab.tab('show');
        }
        // const x = els.filter(el => {
        //   return el.classList.contains('is-invalid');
        // });
      });

    self.fv = [fv1];

    //Dropzone initialization
    Dropzone.autoDiscover = false;
    self.dz = new DropZoneLoader({
      id: '#custom-dropzone',
      autoProcessQueue: false,
      thumbnailWidth: 200,
      thumbnailHeight: 200,
      class: 'm-auto',
      maxFiles: 1,
      mainImage: false,
      copy_params: {
        original: true,
        sizes: {}
      },
      crop: {
        ready: cr => {
          cr.aspect_ratio_x = 1;
          cr.aspect_ratio_y = 1;
        }
      },
      buttons: {
        reorder: false
      },
      onSuccess: function(file, ret) {
        //self.fv[0].revalidateField('has_images');
      },
      onPreviewLoad: function(_t) {
        if (self.toView !== null) {
          let _conf = self.config.default;
          self.dz.removeAllFiles(true);
          // self.dz.reloadImages(self.config.default);
          self.fv[0].validate();
          //aa
        }
      }
    });

    //need to transform wizardActions in a method of Class
    self.wizardActions(function() {
      //self.dz.copy_params.sizes.default = {"w":$('#width').val(),"h":$('#height').val()}
      document
        .getElementById(self.dfId)
        .querySelector("[name='__dz_images']").value = JSON.stringify(
        self.dz.getOrderedDataImages()
      );
      document
        .getElementById(self.dfId)
        .querySelector("[name='__dz_copy_params']").value = JSON.stringify(
        self.dz.copy_params
      );
      // return false;
    });

    self.callbacks.view = view(self);

    self.callbacks.update.onSuccess = () => {
      self.tabs['telefones-e-endereco'].tab.tab('show');
    };

    self.callbacks.delete.onSuccess = data => {
      console.log('no success do delete');
      self.callbacks.unload(self);
    };

    self.override.create.onSuccess = data => {
      if (data.success) {
        // try {
        //   self.tabs['listar'].setState(true);
        // } catch (err) { }
        self.callbacks.create.onSuccess(data);
        HoldOn.close();
        swal({
          title: 'Cadastro efetuado com sucesso!',
          confirmButtonText: 'OK',
          type: 'success',
          onClose: function() {
            self.unload(self);
            self.view(data.data.id);
            // setTimeout(() => {
            //   self.tabs['historico'].tab.tab('show');
            // }, 100)
          }
        });
      }
    };

    // self.callbacks.update.onSuccess = () => {
    //   swal({
    //     title: 'Sucesso',
    //     text: 'Registro atualizado com sucesso!',
    //     type: 'success',
    //     confirmButtonText: 'OK',
    //     onClose: function () {
    //       self.unload(self);
    //       location.reload();
    //     }
    //   });
    // };

    self.callbacks.unload = self => {
      self.tabs['formacao-academica'].tab.addClass('disabled');
      $('#cpf, #nome, #email, #address, #address2, #city,#city_id, #state').val(
        ''
      );

      self.dz.removeAllFiles(true);
    };

    self.onNew = self => {
      self.unload(self);
      document.location.reload(); // self.unload()
      // self.callbacks.unload(self)
    };
  }
); //the end ??

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                                                                                                            
  ██╗      ██████╗  ██████╗ █████╗ ██╗         ███╗   ███╗███████╗████████╗██╗  ██╗ ██████╗ ██████╗ ███████╗
  ██║     ██╔═══██╗██╔════╝██╔══██╗██║         ████╗ ████║██╔════╝╚══██╔══╝██║  ██║██╔═══██╗██╔══██╗██╔════╝
  ██║     ██║   ██║██║     ███████║██║         ██╔████╔██║█████╗     ██║   ███████║██║   ██║██║  ██║███████╗
  ██║     ██║   ██║██║     ██╔══██║██║         ██║╚██╔╝██║██╔══╝     ██║   ██╔══██║██║   ██║██║  ██║╚════██║
  ███████╗╚██████╔╝╚██████╗██║  ██║███████╗    ██║ ╚═╝ ██║███████╗   ██║   ██║  ██║╚██████╔╝██████╔╝███████║
  ╚══════╝ ╚═════╝  ╚═════╝╚═╝  ╚═╝╚══════╝    ╚═╝     ╚═╝╚══════╝   ╚═╝   ╚═╝  ╚═╝ ╚═════╝ ╚═════╝ ╚══════╝
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function isAvalisado(s) {}

function getCep(value) {
  return new Promise(function(resolve, reject) {
    if (value.replace(/\D/g, '').length < 8)
      resolve({
        valid: false,
        message: 'Cep Inválido!',
        meta: {
          data: null
        }
      });
    else {
      delete $.ajaxSettings.headers['X-CSRF-Token'];

      $.ajax({
        headers: {
          'Content-Type': 'application/json'
        },
        complete: jqXHR => {
          $.ajaxSettings.headers['X-CSRF-Token'] = laravel_token;
        },
        url: `https://viacep.com.br/ws/${$('#zipCode').cleanVal()}/json`,
        success: data => {
          if (data.erro == true) {
            resolve({
              valid: false,
              message: 'Cep não encontrado!',
              meta: {
                data: null
              }
            });
          } else {
            resolve({
              valid: true,
              meta: {
                data
              }
            });
          }
        }
      });
    }
  });
}

function view(self) {
  return {
    onSuccess: function(data) {
      const d = data;

      // $('#__form_edit').val(d.id);

      // //reload imagens
      self.dz.removeAllFiles(true);

      if (d.group != null) self.dz.reloadImages(d);

      // $('#otica_id').val(d.otica_id);

      $('#cpf')
        .val(d.cpf)
        .attr('readonly', true)
        .trigger('input');

      self.fv[0].revalidateField('cpf');

      $('#nome').val(d.nome);
      $('#sexo').val(d.sexo);

      if (d.dt_nascimento !== null) {
        var dtn = d.dt_nascimento.split('-');
        $('#dt_nascimento')
          .pickadate('picker')
          .set('select', [dtn[0], dtn[1] - 1, dtn[2]]);
      }

      $('#telefone1')
        .val(d.telefone1)
        .trigger('input');

      $('#telefone2')
        .val(d.telefone2)
        .trigger('input');

      $('#celular1')
        .val(d.celular1)
        .trigger('input');

      $('#celular2')
        .val(d.celular2)
        .trigger('input');

      $('#email')
        .val(d.email)
        .trigger('input');

      $('#zipCode')
        .val(d.zipCode)
        .trigger('input');

      getCep(d.zipCode).then(el => {
        setCEP(el.meta.data, self);

        $('#address').val(d.address);
        $('#address2').val(d.address2);

        $('#resumo').val(d.resumo);
      });

      self.tabs['formacao-academica'].tab.removeClass('disabled');
      document.getElementById('user_name').firstChild.nodeValue = d.nome;
    },
    onError: function(self) {
      console.log('executa algo no erro do callback', this);
    }
  };
}

function setCEP(data, self) {
  const _conf = self.toView;

  if (self.toView !== null && $('#zipCode').val() == _conf.zipCode) {
    if ($('#address').val() == '' && _conf.address !== '') {
      $('#address').val(_conf.address);
    }

    if ($('#address2').val() == '' && _conf.address2 !== '')
      $('#address2').val(_conf.address2);

    $('#city_id').val(data.ibge);
    $('#city').val(data.localidade);
    $('#state').val(data.uf);
    $('#address').focus();
  } else {
    if (data !== null) {
      //com logradouro
      if (data.logradouro) {
        $('#address').val(`${data.logradouro}`);
        $('#address2').val(data.bairro);
      }

      $('#city').val(data.localidade);
      $('#city_id').val(data.ibge);
      $('#state').val(data.uf);
      $('#address').focus();
    } else $('#address, #address2, #city, #state').val('');
  }

  self.fv[0].revalidateField('address');
  self.fv[0].revalidateField('address2');
}
