

$(document).ready(function(){
    window.console && console.log('ready funtion running');
    countPos = 0;

    function add_position_element() {
        $('#position_container').append(
            '<div id="position'+countPos+'" style="display: block"> \
                <label for="year'+countPos+'">Year:</label> \
                <input type="text" name="year'+countPos+'" id="year'+countPos+'"> \
                <!-- remove current position section + cancel default button post --> \
                <input type="button" name="removePosition'+countPos+'" id="removePosition'+countPos+'" class="removePosition" value="-"></br>\
                <textarea name="desc'+countPos+'" id="desc'+countPos+'"></textarea> \
            </div>'
        );
    }

    $('#position_button').click(function(event){
        event.preventDefault();
        // to account for possible php injections
        countPos = $('#position_container').children().length;
        countPos ++;
        if(countPos > 9){
            alert("Maximum of 9 Positions may be added");
            countPos --;
            return;
        }
        window.console && console.log('Adding position: '+countPos);
        add_position_element();
    });

    //renames all childrens' id, for, name attributes in this parent
    function decrementPositionSections(parent){
        let parent_id = parent.attr('id');
        let removedPosRank = parseInt(parent_id.slice(-1));
        let newRank = removedPosRank - 1;
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

        parent.children('textarea').each(function(){
            let child = $(this);
            let generic_name = child.attr('name').slice(0,-1);
            let generic_id = child.attr('id').slice(0,-1);
            child.attr({
                    'name': generic_name+newRank,
                    'id': generic_id+newRank 
            });
        });
    }

    // Use Container to handle clicks on dynamically added .removePosition buttons
    $('#position_container').on('click', '.removePosition', function(event){
        event.preventDefault();
        // to account for possible php injections
        countPos = $('#position_container').children().length;
        countPos--;
        let parent = $(this).parent();
        let parent_id = parent.attr('id');
        let removedPosRank = parseInt(parent_id.slice(-1));
        
        const position_container = $('#position_container');

        window.console && console.log('rank: '+removedPosRank + ' ');
        
        for(let i = removedPosRank+1; i<= position_container.children().length; i++){
            let section = position_container.children(':nth-child('+i+')');

            window.console && console.log('Updating rank: '+(i));
            decrementPositionSections(section);
        }
        
        // remove of the current section has to be last in order for function to run fully
        parent.remove();
    });
});


