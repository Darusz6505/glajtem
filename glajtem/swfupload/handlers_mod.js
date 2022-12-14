function preLoad() {
	if (!this.support.loading) {
		alert("You need the Flash Player to use SWFUpload.");
		return false;
	} else if (!this.support.imageResize) {
		alert("You need Flash Player 10 to upload resized images.");
		return false;
	}
}

function loadFailed() {
	alert("Something went wrong while loading SWFUpload. If this were a real application we'd clean up and then give you an alternative");
}

function fileQueueError(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert(errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			imageName = "zerobyte.gif";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			imageName = "toobig.gif";
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			alert('ERROR(1): ' + message);
			break;
		}

		addImage("./swfupload/images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startResizedUpload(this.getFile(0).ID, this.customSettings.thumbnail_width, this.customSettings.thumbnail_height, SWFUpload.RESIZE_ENCODING.JPEG, this.customSettings.thumbnail_quality, false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadProgress(file, bytesLoaded) {

	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);

		var progress = new FileProgress(file, this.customSettings.upload_target);
		progress.setProgress(percent);
		progress.setStatus("Uploading...");
		progress.toggleCancel(true, this);
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.upload_target);

		if(serverData.substring(0, 7) === "FILEID:") {

		   //alert('FILEID:' + serverData.substring(7).split('#',1));

			addImage("./thumbnail.php?id=" + serverData.substring(7).split('#',1));
			// -bo home.pl dokleja do tego jakie?? swoje skrypty !!! 2013-02-08

			progress.setStatus("Upload Complete.");
			progress.toggleCancel(false);
		}
		else
		{
			addImage("./swfupload/images/error.gif");
			progress.setStatus("Error.");
			progress.toggleCancel(false);

			alert('ERROR(2): ' + serverData);
		}


	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startResizedUpload(this.getFile(0).ID, this.customSettings.thumbnail_width, this.customSettings.thumbnail_height, SWFUpload.RESIZE_ENCODING.JPEG, this.customSettings.thumbnail_quality, false);
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("Wszystkie zdj??cia pobrane."); //All images received
			progress.toggleCancel(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Cancelled");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Stopped");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert('ERROR(3): ' + message);
			break;
		}

		addImage("./swfupload/images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}


function addImage(src) {

   //alert(src);

	var newImg = document.createElement("img");
	/*
	newImg.style.margin = "5px";
	newImg.style.verticalAlign = "middle"; */

	newImg.style.margin = "1em auto";
	newImg.style.display = "block";

	var divThumbs = document.getElementById("swf_thumbnails");

	var plik = src.split('=', 2);
		 plik = plik[1];

	var div = '<div id=\'temp\' class=\'swf_formPlace\'>'+"\n";

		 div += '<p>...' + plik.substring(10) + '</p>';
		 div += '<input type=\'hidden\' name="plik[]" value="' + plik + '" />' + "\n";
		 div += '<div><label for=\'tyt\'>tytu??</label><input type=\'text\' for=\'tyt\' name="tyt[]" /></div>' + "\n";
		 div += '<div><label for=\'opis\'>opis</label><input type=\'text\' for=\'opis\' name="opis[]" /></div>' + "\n";
		 div += '<div><label for=\'kasuj\'>kasuj  </label> <input type=\'checkbox\' id=\'kasuj\' name="kas[' + plik + ']" />' + "\n";
		 div += '<label for=\'skip\'>pomi?? </label>  <input type=\'checkbox\' name="skip['+plik+']" id="skip" /></div>' + "\n";
		 div += '</div>' + "\n";

   	div = $j(div);

	div.insertBefore(divThumbs);

	var divTemp = document.getElementById('temp');

	divTemp.insertBefore(newImg, divTemp.firstChild);

   divTemp.removeAttribute('id');

	//divThumbs.insertBefore(newImg, divThumbs.firstChild);  //-tak by??o
	//document.getElementById("thumbnails").appendChild(newImg);

	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else
	{
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);


		ht = $j('#swf_ster_form').html();

		 //alert(ht);

		 if(!ht)
		 {
		  ht = '<div id=\'swf_ster_form\'>';
		  ht += '<input type=\'submit\' value=\'wczytaj\'>';
		  ht += '</div>';

		  $j(ht).insertAfter('#swf_thumbnails');
		 }



		//$j('#swf_thumbnails img:not(.swf_thumbs)').loadImg().addClass('swf_thumbs'); //.css({'border': '1px solid red'});

	};

	alert(src);

	newImg.src = src;

}

function fadeIn(element, opacity) {
	var reduceOpacityBy = 50;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}



/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID) {
	this.fileProgressID = "divFileProgress";

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
		fadeIn(this.fileProgressWrapper, 0);

	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfuploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfuploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};
