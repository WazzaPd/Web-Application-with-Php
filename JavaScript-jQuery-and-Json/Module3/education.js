

$(document).ready(function(){
    window.console && console.log('ready funtion running');
    countEdu = 0;

    function add_education_element(){
        $('#education_container').append(
            '<div id="education'+countEdu+'" style="display: block"> \
                <label for="year'+countEdu+'">Year:</label> \
                <input type="text" name="year'+countEdu+'" id="year'+countEdu+'"> \
                <!-- remove current education section + cancel default button post --> \
                <input type="button" name="removeEducation'+countEdu+'" id="removeEducation'+countEdu+'" class="removeEducation"  value="-"> </br>\
                <label for="school'+countEdu+'">School:</label> \
                <input type="text" name="school'+countEdu+'" id="school'+countEdu+'" class="school"> \
            </div>'
        );
    }

    $('#education_button').click(function(event){
        event.preventDefault();
        countEdu ++;
        if(countEdu > 9){
            alert("Maximum of 9 Educations may be added");
            countEdu --;
            return;
        }
        window.console && console.log('Adding education: '+countEdu);
        add_education_element();
    });

    //renames all childrens' id, for, name attributes in this parent
    function decrementEducationSections(parent){
        let parent_id = parent.attr('id');
        let removedEduRank = parseInt(parent_id.slice(-1));
        let newRank = removedEduRank - 1;
        let newParentId = parent.attr('id').slice(0,-1) + newRank

        parent.attr('id', newParentId);

        window.console && console.log('old id:' + parent_id + " new id: "+ parent.attr('id'));

        // $(this) refers to childrens
        parent.children('label').each(function(){
            let child = $(this);
            let generic = child.attr('for').slice(0,-1);
            $(this).attr('for', generic+newRank);
        });

        parent.children('input').each(function(){
            let child = $(this);
            let generic_name = child.attr('name').slice(0,-1);
            let generic_id = child.attr('id').slice(0,-1);
            child.attr({
                    'name': generic_name+newRank,
                    'id': generic_id+newRank 
            });
        });
    }

    // Use Container to handle clicks on dynamically added .removeEducation buttons
    $('#education_container').on('click', '.removeEducation', function(event){
        event.preventDefault();
        countEdu--;
        let parent = $(this).parent();
        let parent_id = parent.attr('id');
        let removedEduRank = parseInt(parent_id.slice(-1));
        
        const education_container = $('#education_container');

        window.console && console.log('rank: '+removedEduRank + ' ');
        
        for(let i = removedEduRank+1; i<= education_container.children().length; i++){
            let section = education_container.children(':nth-child('+i+')');

            window.console && console.log('Updating rank: '+(i));
            decrementEducationSections(section);
        }
        
        // remove of the current section has to be last in order for function to run fully
        parent.remove();
    });

});
