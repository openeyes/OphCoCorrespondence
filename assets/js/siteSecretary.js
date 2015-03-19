/**
 * Created by petergallagher on 18/03/15.
 */
var OpenEyes = OpenEyes || {};
OpenEyes.CO = OpenEyes.CO || {};

OpenEyes.CO.SiteSecretary = (function(){
    "use strict";
    var $editForm,
        $editFormTable,
        $saveRow,
        $lastEdit,
        $blankEdit,
        saveButton = '<button class="addButton">Add</button>',
        deleteButton = '<button type="submit" form="deleteSecretaryForm" name="id" value="">delete</button>',
        addUrl = "/OphCoCorrespondence/admin/addSiteSecretary",
        postData = {};

    return {
        addEvent: function(e){
            e.preventDefault();
            var $targetButton = $(e.target),
                $targetRow = $targetButton.parents(".secretaryFormRow");

            $targetRow.find(':input').serializeArray().map(function(x){postData[x.name] = x.value;});
            postData.YII_CSRF_TOKEN = $editForm.find(':input[name="YII_CSRF_TOKEN"]').val();

            $.ajax(addUrl, {
                type: "POST",
                data: postData,
                dataType: 'json',
                success: function(data, status, xhr){
                    console.log(data.errors.length);
                    if(data.errors.length){

                    } else {
                        $targetButton.replaceWith(deleteButton);
                        $editFormTable.children('tbody').append($blankEdit);
                        $targetRow.find('button[form="deleteSecretaryForm"]').val(data.siteSecretaries[0].id);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert('Error saving contact');
                }
            });
        },
        init: function(){
            $editForm = $('#editSecretaryForm');
            $editFormTable = $editForm.children('table');
            $saveRow = $editFormTable.find('tr:last');
            $lastEdit = $saveRow.prev();
            $lastEdit.find('button[form="deleteSecretaryForm"]').replaceWith(saveButton);
            $blankEdit = $lastEdit.clone();

            //We'll handle these with fancy JS actions now so remove them
            $saveRow.remove();
            $editForm.on('click', '.addButton', this.addEvent);
        }
    };
})();

$(function(){
    OpenEyes.CO.SiteSecretary.init();
});
