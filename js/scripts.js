

function openDialog(myHeading, myContent) {


	var contentblock = $('#contentblock');
	var dialog = $('#dialog');
	var heading = $('#dheadercontent');
	var content = $('#dcontent');


	heading.html(myHeading);
	content.html(myContent);


	contentblock.show();
	dialog.show();


}

function closeDialog() {


	var contentblock = $('#contentblock');
	var dialog = $('#dialog');


	dialog.hide();
	contentblock.hide();	


}





function displayFireSite() {


	$.ajax({
          
        url: 'php/ajax.php',
        type: 'post',
        dataType: 'script',
        data: { ajax: 'displayFireSite', wid: $('#websites').val(), pid: $('#pages').val() },
        dataType: 'json'
        
    })
        
    .done(function (answer) {


    	var mainContainer = $('#mainContainer');

    	mainContainer.html('<div class="addRow"></div>');


    	if(answer.readMe != 'none') {


    		for(var i = 0; i < answer.length; i++) {


    			var myRow = '<div class="section group" data-add="0" id="row' + answer[i].id + '">';


    			var boxes = answer[i].layout.split(',');


    			for(var j = 0; j < boxes.length; j++) {


    				myRow += '<div class="col span_' + boxes[j] + '_of_12" id="box' + answer[i].id + '-' + j + '" ';

    				if(i == 0) {
    					myRow += 'data-row="0"';
    				}
    				if(i > 0 && i < (answer.length - 1)) {
    					myRow += 'data-row="1"';
    				}
    				if(i == (answer.length - 1)) {
    					myRow += 'data-row="2"';
    				}

    				myRow += ' data-plugin="' + answer[i]['plugin'][j] + '">' + answer[i]['content'][j] + '</div>';


    			}


    			myRow += '</div><div class="addRow" data-add="' + (i + 1) + '"></div>';


    			mainContainer.append(myRow);


    		}



    		$('.col').hover(function() {
    			
    			var myBox = $(this);

    			var pos = myBox.offset();
    			var top = pos.top;
    			var left = pos.left - 241;

    			if(left < 49) {
    				var rt = myBox.offset().left + myBox.outerWidth() - 207;
    				left = rt;
    			}

    			myBox.css('border', '4px solid #ECEFF1');

    			showBoxToolbar(myBox.attr('id'), top, left);


    		}, function() {
    			
    			var myBox = $(this);

    			myBox.css('border', '');

    			hideBoxToolbar();

    		});



    		$('.addRow').hover(function(e) {
    			
    			var addRow = $(this);

    			var pos = addRow.offset();
    			var top = pos.top - 17;

    			addRow.css('background', '#ECEFF1');

    			addRow.html('<div id="addRowIcon" style="top:' + top + 'px;" title="Neue Reihe einfügen" onclick="showAddRow(\'' + addRow.attr('data-add') + '\');"><i class="fa fa-plus fa-fw fa-1x"></i></div>');


    		}, function() {
    			
    			var addRow = $(this);

    			addRow.css('background', '');
    			$('#addRowIcon').remove();

    		});



    	}


    });


}



function showBoxToolbar(id, top, left) {


	var myBox = $('#'+id);

	myBox.append('<div id="boxToolbar" style="top:' + top + 'px;left:' + left + 'px;display:inline-block;" contenteditable="false"><div class="action" id="boxToolbarUp" onclick="moveRow(\'' + id + '\', \'up\');"><i class="fa fa-chevron-up fa-fw fa-1x" title="Reihe nach oben verschieben"></i></div><div class="action" id="boxToolbarDown" onclick="moveRow(\'' + id + '\', \'down\');"><i class="fa fa-chevron-down fa-fw fa-1x" title="Reihe nach unten verschieben"></i></div><div class="action" id="boxToolbarEdit"><i class="fa fa-pencil fa-fw fa-1x" title="Inhalt editieren"></i></div><div class="action" id="boxToolbarConfig"><i class="fa fa-wrench fa-fw fa-1x" title="Plugin editieren"></i></div><div class="action" id="boxToolbarDesign"><i class="fa fa-tint fa-fw fa-1x" title="Design editieren"></i></div><div class="action" id="boxToolbarSave"><i class="fa fa-check fa-fw fa-1x" title="Inhalt speichern"></i></div><div class="action" id="boxToolbarCancel"><i class="fa fa-times fa-fw fa-1x" title="Inhalt verwerfen"></i></div><div class="action" id="boxToolbarDelete"><i class="fa fa-trash fa-fw fa-1x" title="Reihe löschen"></i></div></div>');


	var row = myBox.attr('data-row');

	if(row == 0) {
		$('#boxToolbarUp').hide();
	}
	if(row == 2) {
		$('#boxToolbarDown').hide();
	}


}


function hideBoxToolbar() {

	$('#boxToolbar').remove();

}



function moveRow(id, direction) {


	var realid = id.replace('box', '').split('-');
	var rowid = realid[0];
	


	$.ajax({
          
        url: 'php/ajax.php',
        type: 'post',
        dataType: 'script',
        data: { ajax: 'moveRow', wid: $('#websites').val(), pid: $('#pages').val(), rowid: rowid, direction: direction },
        dataType: 'text'
        
    })
        
    .done(function (answer) {
    	

    	displayFireSite();


    });


}



function showAddRow(rownr) {


	openDialog('test', 'blub');


}