new IOService(
  {
    name: 'Formacao',
    dfId: 'form-form',
    path: 'vitrine',
    wz: $('#form-wizard').wizard()
  },
  function(self) {
    setTimeout(() => {
      self.tabs['formacao-academica'].tab.on('shown.bs.tab', e => {
        console.log(IO.services);
        IO.active = self;
        self.dt.ajax.url(
          `${self.path}/formacao/list/${IO.services.vitrine.toView &&
            IO.services.vitrine.toView.id}`
        );
        self.dt.ajax.reload();
        self.dt.columns.adjust();
      });
    });

    // $('#status').on('change', function(e) {
    //   const val = $(e.currentTarget).val();
    //   if (['Avalisado', 'Bloqueado', 'De Risco'].includes(val)) {
    //     // self.tabs['outras-observacoes'].tab.tab('show');
    //     self.fv[0]
    //       .enableValidator('details', 'notEmpty')
    //       .revalidateField('details');
    //   } else {
    //     self.fv[0]
    //       .disableValidator('details', 'notEmpty')
    //       .revalidateField('details');
    //   }

    //   if (val === 'Bloqueado' || val === 'Inativo') {
    //     self.fv[0].disableValidator('vl_compra').revalidateField('vl_compra');
    //     $('#vl_compra, #vl_entrada, #product')
    //       .val('')
    //       .attr('readonly', true);
    //   } else {
    //     self.fv[0].enableValidator('vl_compra').revalidateField('vl_compra');
    //     $('#vl_compra, #vl_entrada, #product').removeAttr('readonly');
    //   }

    //   $('#details').focus();
    // });

    // $('#vl_compra, #vl_entrada').maskMoney({
    //   prefix: 'R$ ',
    //   decimal: ',',
    //   thousands: '.'
    // });

    // $('#vl_compra').on('keyup', function(e) {
    //   self.fv[0].revalidateField($(this).attr('id'));
    // });

    $('#form_inicio').mask('0000', {
      onComplete: function(val, e, field) {
        $('#form_fim').focus();
      }
    });

    $('#form_fim').mask('0000');

    self.override.create.onSuccess = data => {
      if (data.success) {
        self.callbacks.create.onSuccess(data);
        HoldOn.close();
        swal({
          title: 'formação cadastrada com sucesso!',
          confirmButtonText: 'OK',
          type: 'success',
          onClose: function() {
            self.callbacks.unload(self);
            self.dt.ajax.reload();
            self.dt.columns.adjust();
          }
        });
      }
    };

    //Datatables initialization
    self.dt = $('#form-table')
      .DataTable({
        ajax: null,
        aaSorting: [[1, 'desc']],
        initComplete: function() {
          let api = this.api();
          $.fn.dataTable.defaults.initComplete(this);
        },
        footerCallback: function(row, data, start, end, display) {},
        columns: [
          { data: 'vid', name: 'id' },
          { data: 'order', name: 'order' },
          { data: 'category', name: 'tipo' },
          { data: 'curso', name: 'nome_do_curso' },
          { data: 'instituicao', name: 'instituicao' },
          { data: 'inicio', name: 'inicio' },
          { data: 'fim', name: 'conclusao' },
          { data: null }
        ],
        columnDefs: [
          { targets: '__dt_', width: '3%', searchable: true, orderable: true },
          {
            targets: '__dt_order',
            width: '3%',
            orderable: true,
            visible: false
          },
          {
            targets: '__dt_tipo',
            searchable: true,
            orderable: true,
            width: '10%'
          },
          {
            targets: '__dt_nome_do_curso',
            searchable: true,
            orderable: true,
            width: 'auto'
          },
          {
            targets: '__dt_instituicao',
            orderable: true,
            width: '25%',
            className: 'text-center'
          },
          {
            targets: '__dt_inicio',
            orderable: true,
            width: '10%',
            className: 'text-center'
          },
          {
            targets: '__dt_conclusao',
            className: 'text-center',
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
                  {
                    ico: 'ico-edit',
                    _class: 'text-info',
                    title: 'editar'
                  },
                  { ico: 'ico-trash', _class: 'text-danger', title: 'excluir' }
                ]
              });
            }
          }
        ]
      })
      .on('click', '.ico-trash', function() {
        var data = self.dt.row($(this).parents('tr')).data();
        self.delete(data.vid, {
          url: `${self.path}/formacao/delete/${data.vid}`
        });
      })
      .on('click', '.btn-dt-button[data-original-title=editar]', function() {
        var data = self.dt.row($(this).parents('tr')).data();

        IO.active.view(data.vid);
      })
      .on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
      });

    let form = document.getElementById('form-form');

    let fv1 = FormValidation.formValidation(
      form.querySelector('.step-pane[data-step="1"]'),
      {
        fields: {
          // dt_compra: {
          //   validators: {
          //     notEmpty: {
          //       message: 'Data Obrigatória'
          //     },
          //     date: {
          //       format: 'DD/MM/YYYY',
          //       message: 'Informe uma data válida!'
          //     }
          //   }
          // },
          // vl_compra: {
          //   validators: {
          //     callback: {
          //       message: 'Valor não pode ser 0!',
          //       callback: function(value, validator, $field) {
          //         let v = $('#vl_compra').maskMoney('unmasked')[0];
          //         return v > 0;
          //       }
          //     }
          //   }
          // },
          form_curso: {
            validators: {
              notEmpty: {
                message: 'Campo curso obrigatório!'
              }
            }
          },
          form_instituicao: {
            validators: {
              notEmpty: {
                message: 'Campo instituição obrigatório!'
              }
            }
          },
          form_inicio: {
            validators: {
              notEmpty: {
                message: 'Data inicio obrigatória!'
              }
            }
          }
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
      .on('core.validator.validated', function(e) {});

    self.fv = [fv1];

    //need to transform wizardActions in a method of Class
    self.wizardActions(function() {
      // self.extraData.vl_entrada_clean = $('#vl_entrada').maskMoney(
      //   'unmasked'
      // )[0];
      // self.extraData.vl_compra_clean = $('#vl_compra').maskMoney('unmasked')[0];
      self.extraData.vitrineId = IO.services.vitrine.toView.id;
    });

    self.onNew = self => {
      self.unload(self);
      document.location.reload(); // self.unload()
      // self.callbacks.unload(self)
    };

    IO.services.formacao.view = function(id) {
      console.log('do nothing');
      // self.unload(this);
      $.ajax({
        url: `${self.path}/formacao/view/${id}`,
        beforeSend: function() {
          HoldOn.open({
            message: 'Carregando dados, aguarde...',
            theme: 'sk-bounce'
          });
        },
        success: ret => {
          if (ret.success) {
            var data = ret.data[0];
            this.toView = data;

            console.log('aa');
            this.callbacks.view.onSuccess(data);
          }
          HoldOn.close();
        },
        error: ret => {
          this.defaults.ajax.onError(ret, this.callbacks.view.onError);
          this.toView = data;
          HoldOn.close();
        }
      });
    };

    IO.services.formacao.update = function(data) {
      let serialized = this.df.serializeArray().concat(this.getExtraData());

      serialized.push({ name: 'isUpdate', value: data.vid });
      $.ajax({
        method: 'POST',
        url: `${this.path}/formacao/update/${data.vid}`,
        cache: false,
        dataType: 'json',
        data: serialized,
        beforeSend: function() {
          HoldOn.open({
            message: 'Atualizando dados, aguarde...',
            theme: 'sk-bounce'
          });
        },
        success: ret => {
          HoldOn.close();
          if (ret.success) {
            try {
              //set list tab as updatable
              this.tabs['listar'].setState(true);
              this.callbacks.update.onSuccess(ret);
              swal({
                title: 'Sucesso',
                text: 'O registro foi atualizado com sucesso!',
                type: 'success',
                confirmButtonText: 'OK',
                onClose: () => {
                  this.callbacks.unload(self);
                }
              });
            } catch (err) {
              this.callbacks.update.onSuccess(ret);
            }
          }
        },
        error: ret => {
          this.defaults.ajax.onError(ret, this.callbacks.update.onError);
        }
      });
    };

    IO.services.formacao.callbacks.view = formView(IO.services.formacao);
    IO.services.formacao.callbacks.update = formUpdate(IO.services.formacao);

    // self.callbacks.view = histVew(self);

    // self.callbacks.update.onSuccess = () => {
    //   swal({
    //     title: 'Sucesso',
    //     text: 'Configurações atualizadas com sucesso!',
    //     type: 'success',
    //     confirmButtonText: 'OK',
    //     onClose: function () {
    //       self.unload(self);
    //       location.reload();
    //     }
    //   });
    // };

    // self.callbacks.create.onSuccess = (data) => {

    //   if (data.success) {
    //     // self.callbacks.create.onSuccess(data);
    //     HoldOn.close();
    //     swal({
    //       title: 'Histórico cadastrado com sucesso!',
    //       confirmButtonText: 'OK',
    //       type: 'success',
    //       onClose: function () {
    //         // self.unload(self);
    //         //fazer o on Edit, e após habilitar aba de histórico
    //       }
    //     });
    //   }
    // };

    self.callbacks.unload = self => {
      // $('#status').val('')
      $('#form_curso, #form_instituicao, #form_inicio, #form_fim').val('');
      document.getElementById('form_tipo').selectedIndex = 0;
      document.getElementById('form_area').selectedIndex = 0;
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

function formView(self) {
  return {
    onSuccess: function(data) {
      const _conf = data;

      console.log('no fview', data);
      // $('#__form_edit').val(_conf.id);
      $('#form_tipo').val(data.id);
      $('#form_area').val(data.area);
      $('#form_curso').val(data.curso);
      $('#form_instituicao').val(data.instituicao);
      $('#form_inicio').val(data.inicio);
      $('#form_fim').val(data.fim);

      // $('#refs_comerciais').val(_conf.refs_comerciais);
    },
    onError: function(self) {
      console.log('executa algo no erro do callback');
    }
  };
}

function formUpdate(self) {
  return {
    onSuccess: function(data) {
      self.toView = null;
      self.dt.ajax.reload();
      self.dt.columns.adjust();
    },
    onError: function(self) {
      self.toView = null;
      console.log('executa algo no erro do callback');
    }
  };
}

// function delete(self) {
//   return {
//     onSuccess: function(data) {
//       const _conf = data;
//       // $('#__form_edit').val(_conf.id);
//       $('#loja_origem').val(_conf.loja_origem);
//       $('#status').val(d.status);

//       // $('#refs_comerciais').val(_conf.refs_comerciais);
//     },
//     onError: function(self) {
//       console.log('executa algo no erro do callback');
//     }
//   };
// }
