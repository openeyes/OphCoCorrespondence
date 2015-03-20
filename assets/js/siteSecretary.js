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
        $targetButton,
        $targetRow,
        saveButton = '<button class="addButton">Add</button>',
        deleteButton = '<button type="submit" form="deleteSecretaryForm" name="id" value="">delete</button>',
        addUrl = "/OphCoCorrespondence/admin/addSiteSecretary",
        deleteUrl = "/OphCoCorrespondence/admin/deleteSiteSecretary",
        postData = {},
        errorTmpl = '<div class="alert-box alert with-icon">' +
                            '<p>Please fix the following input errors:</p>' +
                            '<ul>{{#errors}}<li>{{.}}</li>{{/errors}}</ul>' +
                    '</div>';

    function addSuccess(data, status, xhr){
        if(!data.success){
            $editForm.parent().prepend(Mustache.render(errorTmpl, data));
        } else {
            $targetButton.replaceWith(deleteButton);
            $editFormTable.children('tbody').append($blankEdit);
            $targetRow.find('button[form="deleteSecretaryForm"]').val(data.siteSecretaries[0].id);
        }
    }

    function deleteSuccess(data, status, xhr){
        $targetRow.remove();
    }

    function formEvent(e){
        e.preventDefault();
        var url,
            successFunction;

        $targetButton = $(e.target);
        $targetRow = $targetButton.parents(".secretaryFormRow");
        postData.YII_CSRF_TOKEN = $editForm.find(':input[name="YII_CSRF_TOKEN"]').val();

        if($targetButton.hasClass('addButton')){
            $targetRow.find(':input').serializeArray().map(function(x){postData[x.name] = x.value;});
            url = addUrl;
            successFunction = addSuccess;
        } else {
            url = deleteUrl;
            successFunction = deleteSuccess;
            postData.id = $targetButton.val();
        }

        $.ajax(url, {
            type: "POST",
            data: postData,
            dataType: 'json',
            success: successFunction,
            error: function(jqXHR, textStatus, errorThrown){
                alert('Error saving contact');
            }
        });
    }

    return {

        init: function(){
            $editForm = $('#editSecretaryForm');
            $editFormTable = $editForm.children('table');
            $saveRow = $editFormTable.find('tr:last');
            $lastEdit = $saveRow.prev();
            $lastEdit.find('button[form="deleteSecretaryForm"]').replaceWith(saveButton);
            $blankEdit = $lastEdit.clone();

            //We'll handle these with fancy JS actions now so remove them
            $saveRow.remove();
            $editForm.on('click', 'button[form="deleteSecretaryForm"], .addButton', formEvent);

        }
    };
})();

$(function(){
    OpenEyes.CO.SiteSecretary.init();
});
