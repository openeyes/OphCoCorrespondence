/**
 * Created by petergallagher on 18/03/15.
 */
var OpenEyes = OpenEyes || {};
OpenEyes.CO = OpenEyes.CO || {};

OpenEyes.CO.SiteSecretary = (function(){
    "use strict";
    var editForm,
        editFormTable,
        saveRow,
        lastEdit,
        saveButton,
        blankEdit,
        addUrl = "/OphCoCorrespondence/admin/addSiteSecretary",
        postData = {};

    return {
        addEvent: function(e){
            e.preventDefault();
            postData.FirmSiteSecretary = $(e.target).parents(".secretaryFormRow").find(':input').serialize();
            $.ajax(addUrl, {
                type: "POST",
                data: postData,
                success: function(data, status, xhr){
                    editFormTable.append(blankEdit);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert('Error saving contact');
                }
            });
        },
        init: function(){
            editForm = $('#editSecretaryForm');
            editFormTable = editForm.children('table');
            saveRow = editFormTable.find('tr:last');
            lastEdit = saveRow.prev();
            saveButton = '<button class="addButton">Add</button>';

            lastEdit.find('button[form="deleteSecretaryForm"]').replaceWith(saveButton);
            blankEdit = lastEdit.clone();
            //We'll handle these with fancy JS actions now so remove them
            saveRow.remove();

            $('.addButton').on('click', this.addEvent);

        }
    };
})();

$(function(){
    OpenEyes.CO.SiteSecretary.init();
});
