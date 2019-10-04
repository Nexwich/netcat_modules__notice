(function ($){
  $(function (){

    var system, catalogue, subdivision, sub_class, message, user,
      lang = {
        "INSERT_VARIABLES_SYSTEM": "Свойство...",
        "INSERT_VARIABLES_CATALOGUE": "Cайт",
        "INSERT_VARIABLES_SUBDIVISION": "Раздел",
        "INSERT_VARIABLES_SUB_CLASS": "Инфоблок",
        "INSERT_VARIABLES_MESSAGE": "Объект",
        "INSERT_VARIABLES_USER": "Пользователь"
      };

    if($('.no_cm').length){
      $.ajax({
        url: "/netcat/modules/notice/admin/index.php",
        type: 'GET',
        async: false,
        data: {
          controller: 'select',
          action: 'json',
          dataType: 'json'
        },
        success: function (response){
          system = response.system;
          catalogue = response.catalogue;
          subdivision = response.subdivision;
          sub_class = response.sub_class;
          message = response.message;
          user = response.user;
        }
      });

      CKEDITOR.plugins.add('panel_variables_system', {
        requires: ['richcombo'],
        init: function (editor){
          editor.ui.addRichCombo('panel_variables_system', {
            label: lang.INSERT_VARIABLES_SYSTEM,
            title: lang.INSERT_VARIABLES_SYSTEM,
            voiceLabel: lang.INSERT_VARIABLES_SYSTEM,
            multiSelect: false,
            toolbar: 'mailtoolbar',
            panel: {
              attributes: {'aria-label': ''},
              css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
              voiceLabel: lang.INSERT_VARIABLES_SYSTEM
            },
            init: function (){
              for(var varitable in system){
                var caption = system[varitable];
                this.add(varitable, caption, caption);
              }
            },
            onClick: function (value){
              editor.focus();
              editor.fire('saveSnapshot');
              editor.insertHtml(value);
              editor.fire('saveSnapshot');
            }
          });

        }
      });
      CKEDITOR.plugins.add('panel_variables_catalogue', {
        requires: ['richcombo'],
        init: function (editor){
          editor.ui.addRichCombo('panel_variables_catalogue', {
            label: lang.INSERT_VARIABLES_CATALOGUE,
            title: lang.INSERT_VARIABLES_CATALOGUE,
            voiceLabel: lang.INSERT_VARIABLES_CATALOGUE,
            multiSelect: false,
            toolbar: 'mailtoolbar',
            panel: {
              attributes: {'aria-label': ''},
              css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
              voiceLabel: lang.INSERT_VARIABLES_CATALOGUE
            },
            init: function (){
              for(var varitable in catalogue){
                var caption = catalogue[varitable];
                this.add(varitable, caption, caption);
              }
            },
            onClick: function (value){
              editor.focus();
              editor.fire('saveSnapshot');
              editor.insertHtml(value);
              editor.fire('saveSnapshot');
            }
          });

        }
      });
      CKEDITOR.plugins.add('panel_variables_subdivision', {
        requires: ['richcombo'],
        init: function (editor){
          editor.ui.addRichCombo('panel_variables_subdivision', {
            label: lang.INSERT_VARIABLES_SUBDIVISION,
            title: lang.INSERT_VARIABLES_SUBDIVISION,
            voiceLabel: lang.INSERT_VARIABLES_SUBDIVISION,
            multiSelect: false,
            toolbar: 'mailtoolbar',
            panel: {
              attributes: {'aria-label': ''},
              css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
              voiceLabel: lang.INSERT_VARIABLES_SUBDIVISION
            },
            init: function (){
              for(var varitable in subdivision){
                var caption = subdivision[varitable];
                this.add(varitable, caption, caption);
              }
            },
            onClick: function (value){
              editor.focus();
              editor.fire('saveSnapshot');
              editor.insertHtml(value);
              editor.fire('saveSnapshot');
            }
          });

        }
      });
      CKEDITOR.plugins.add('panel_variables_sub_class', {
        requires: ['richcombo'],
        init: function (editor){
          editor.ui.addRichCombo('panel_variables_sub_class', {
            label: lang.INSERT_VARIABLES_SUB_CLASS,
            title: lang.INSERT_VARIABLES_SUB_CLASS,
            voiceLabel: lang.INSERT_VARIABLES_SUB_CLASS,
            multiSelect: false,
            toolbar: 'mailtoolbar',
            panel: {
              attributes: {'aria-label': ''},
              css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
              voiceLabel: lang.INSERT_VARIABLES_SUB_CLASS
            },
            init: function (){
              for(var varitable in sub_class){
                var caption = sub_class[varitable];
                this.add(varitable, caption, caption);
              }
            },
            onClick: function (value){
              editor.focus();
              editor.fire('saveSnapshot');
              editor.insertHtml(value);
              editor.fire('saveSnapshot');
            }
          });

        }
      });
      CKEDITOR.plugins.add('panel_variables_message', {
        requires: ['richcombo'],
        init: function (editor){
          editor.ui.addRichCombo('panel_variables_message', {
            label: lang.INSERT_VARIABLES_MESSAGE,
            title: lang.INSERT_VARIABLES_MESSAGE,
            voiceLabel: lang.INSERT_VARIABLES_MESSAGE,
            multiSelect: false,
            toolbar: 'mailtoolbar',
            panel: {
              attributes: {'aria-label': ''},
              css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
              voiceLabel: lang.INSERT_VARIABLES_MESSAGE
            },
            init: function (){
              for(var varitable in message){
                var caption = message[varitable];
                this.add(varitable, caption, caption);
              }
            },
            onClick: function (value){
              editor.focus();
              editor.fire('saveSnapshot');
              editor.insertHtml(value);
              editor.fire('saveSnapshot');
            }
          });

        }
      });
      CKEDITOR.plugins.add('panel_variables_user', {
        requires: ['richcombo'],
        init: function (editor){
          editor.ui.addRichCombo('panel_variables_user', {
            label: lang.INSERT_VARIABLES_USER,
            title: lang.INSERT_VARIABLES_USER,
            voiceLabel: lang.INSERT_VARIABLES_USER,
            multiSelect: false,
            toolbar: 'mailtoolbar',
            panel: {
              attributes: {'aria-label': ''},
              css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
              voiceLabel: lang.INSERT_VARIABLES_USER
            },
            init: function (){
              for(var varitable in user){
                var caption = user[varitable];
                this.add(varitable, caption, caption);
              }
            },
            onClick: function (value){
              editor.focus();
              editor.fire('saveSnapshot');
              editor.insertHtml(value);
              editor.fire('saveSnapshot');
            }
          });

        }
      });
    }

    CKEDITOR.config.protectedSource.push(/<\?.+?\?>/g);

  });
})(jQuery);

function nc_notice_editor(inputFieldId){
  var editorConfig = {
    toolbarGroups: ["mode", {"name": 'tools'}, {"name": "clipboard"}, {"name": "mathjax"}, {"name": "undo"}, {"name": "find"}, {"name": "selection"}, {"name": "forms"}, {"name": "basicstyles"}, {"name": "cleanup"}, {"name": "list"}, {"name": "indent"}, {"name": "blocks"}, {"name": "align"}, {"name": "links"}, {"name": "insert"}, {"name": "styles"}, {"name": "colors"}],
    extraPlugins: 'panel_variables_system,panel_variables_catalogue,panel_variables_subdivision,panel_variables_sub_class,panel_variables_message,panel_variables_user',
    skin: 'moono',
    language: 'ru',
    filebrowserBrowseUrl: '/netcat/editors/ckeditor4/filemanager/index.php',
    allowedContent: true,
    entities: true,
    autoParagraph: true,
    fullPage: false,
    protectedSource: [/<\?.+?\?>/g]
  };

  if($('.no_cm').length){
    editorConfig.toolbarGroups.push({name: 'mailtoolbar'});
    var ckeditor = CKEDITOR.replace(inputFieldId, editorConfig);
    $nc('#' + inputFieldId).data('ckeditor', ckeditor);
  }
}
