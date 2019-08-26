<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable=no">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="pdf.js"></script>
<script src="pdf.worker.js"></script>
<style type="text/css">

#upload-button {
	width: 150px;
	display: block;
	margin: 20px auto;
}

#file-to-upload {
	display: none;
}

#pdf-main-container {
	width: 600px;
	margin: 20px auto;
}

#pdf-loader {
	display: none;
	text-align: center;
	color: #999999;
	font-size: 13px;
	line-height: 100px;
	height: 100px;
}

#password-container {
	padding: 50px 0;
	box-sizing: border-box;
	text-align: center;
	display: none;
}

#pdf-password {
	border: 1px solid #cccccc;
	padding: 5px;
	margin: 0 10px 0 0;
	width: 100px;
}

#submit-password {
	background: none;
	border: 1px solid #cccccc;
	padding: 4px;
}

#password-message {
	text-align: center;
	color: red;
	margin: 10px 0 0 0;
	font-size: 13px;
}

#pdf-contents {
	display: none;
}

#pdf-meta {
	overflow: hidden;
	margin: 0 0 20px 0;
}

#pdf-buttons {
	float: left;
}

#page-count-container {
	float: right;
}

#pdf-current-page {
	display: inline;
}

#pdf-total-pages {
	display: inline;
}

#pdf-canvas {
	border: 1px solid rgba(0,0,0,0.2);
	box-sizing: border-box;
}

#page-loader {
	height: 100px;
	line-height: 100px;
	text-align: center;
	display: none;
	color: #999999;
	font-size: 13px;
}

</style>
</head>

<body>

<button id="upload-button">Select PDF</button> 
<input type="file" id="file-to-upload" accept="application/pdf" />

<div id="pdf-main-container">
	<div id="pdf-loader">Loading document ...</div>
	<div id="password-container">
		<input type="password" id="pdf-password" autocomplete="off" placeholder="Password" /><button id="submit-password">Submit</button>
		<div id="password-message"></div>
	</div>
	<div id="pdf-contents">
		<div id="pdf-meta">
			<div id="pdf-buttons">
				<button id="" onclick="location.href='index.php'">Reload</button>
				<button id="pdf-prev">Previous</button>
				<button id="pdf-next">Next</button>
				<input type="number" name="" id="trgPage" placeholder="page">
				<button id="goto">Go</button>
			</div>
			<div id="page-count-container">Page <div id="pdf-current-page"></div> of <div id="pdf-total-pages"></div></div>
		</div>
		<canvas id="pdf-canvas" width="600"></canvas>
		<div id="page-loader">Loading page ...</div>
	</div>
</div>

<script>

var __PDF_DOC,
	__CURRENT_PAGE,
	__TOTAL_PAGES,
	__PAGE_RENDERING_IN_PROGRESS = 0,
	__CANVAS = $('#pdf-canvas').get(0),
	__CANVAS_CTX = __CANVAS.getContext('2d');

function showPDF(pdf_url, password) {
	$("#pdf-loader").show();
	$("#password-container").hide();
	
	PDFJS.getDocument({ url: pdf_url, password: password }).then(function(pdf_doc) {
		__PDF_DOC = pdf_doc;
		__TOTAL_PAGES = __PDF_DOC.numPages;
		
		// Hide the pdf loader and show pdf container in HTML
		$("#pdf-loader").hide();
		$("#password-container").hide();
		$("#pdf-contents").show();
		$("#pdf-total-pages").text(__TOTAL_PAGES);

		// Show the first page
		showPage(1);
	}).catch(function(error) { console.log(error)
		$("#pdf-loader").hide();
		
		if(error.name == 'PasswordException') {
			$("#password-container").show();
			$("#pdf-password").val('');
			$("#password-message").text(error.code == 2 ? error.message : '');
		}
		else {
			$("#upload-button").show();
			alert(error.message);
		}
	});
}

function showPage(page_no) {
	__PAGE_RENDERING_IN_PROGRESS = 1;
	__CURRENT_PAGE = page_no;

	// Disable Prev & Next buttons while page is being loaded
	$("#pdf-next, #pdf-prev").attr('disabled', 'disabled');

	// While page is being rendered hide the canvas and show a loading message
	$("#pdf-canvas").hide();
	$("#page-loader").show();

	// Update current page in HTML
	$("#pdf-current-page").text(page_no);
	
	// Fetch the page
	__PDF_DOC.getPage(page_no).then(function(page) {
		// As the canvas is of a fixed width we need to set the scale of the viewport accordingly
		var scale_required = __CANVAS.width / page.getViewport(1).width;

		// Get viewport of the page at required scale
		var viewport = page.getViewport(scale_required);

		// Set canvas height
		__CANVAS.height = viewport.height;

		var renderContext = {
			canvasContext: __CANVAS_CTX,
			viewport: viewport
		};
		
		// Render the page contents in the canvas
		page.render(renderContext).then(function() {
			__PAGE_RENDERING_IN_PROGRESS = 0;

			// Re-enable Prev & Next buttons
			$("#pdf-next, #pdf-prev").removeAttr('disabled');

			// Show the canvas and hide the page loader
			$("#pdf-canvas").show();
			$("#page-loader").hide();
		});
	});
}

// Upon click this should should trigger click on the #file-to-upload file input element
// This is better than showing the not-good-looking file input element
$("#upload-button").on('click', function() {
	$("#file-to-upload").trigger('click');
});

// When user chooses a PDF file
$("#file-to-upload").on('change', function() {
	// Validate whether PDF
    if(['application/pdf'].indexOf($("#file-to-upload").get(0).files[0].type) == -1) {
        alert('Error : Not a PDF');
        return;
    }

	$("#upload-button").hide();

	// Send the object url of the pdf
	showPDF(URL.createObjectURL($("#file-to-upload").get(0).files[0]), '');
});

$("#submit-password").on('click', function() {
	showPDF(URL.createObjectURL($("#file-to-upload").get(0).files[0]), $("#pdf-password").val());
});

// Previous page of the PDF
$("#pdf-prev").on('click', function() {
	if(__CURRENT_PAGE != 1)
		showPage(--__CURRENT_PAGE);
});

// Next page of the PDF
$("#pdf-next").on('click', function() {
	if(__CURRENT_PAGE != __TOTAL_PAGES)
		console.log(__CURRENT_PAGE)
		showPage(++__CURRENT_PAGE);
});
$("#goto").on('click', function() {
	showPage( parseInt($('#trgPage').val()) );
});

</script>

</body>
</html>