if($)(
	function($){
		$.extend($.fn,{
			uploadify:function(options) {
				$(this).each(function(){
					var settings = $.extend({
					css             : 'upload/style.css',
					id              : $(this).attr('id'), // The ID of the object being Uploadified
					uploader        : 'app/upload/upload.swf', // The path to the uploadify swf file
					script          : 'up.php', // The path to the uploadify backend upload script
					expressInstall  : null, // The path to the express install swf file
					folder          : '', // The path to the upload folder
					height          : 30, // The height of the flash button
					width           : 120, // The width of the flash button
					cancelImg       : 'app/upload/cancel.png', // The path to the cancel image for the default file queue item container
					wmode           : 'opaque', // The wmode of the flash file
					scriptAccess    : 'sameDomain', // Set to "always" to allow script access across domains
					fileDataName    : 'file', // The name of the file collection object in the backend upload script
					method          : 'POST', // The method for sending variables to the backend upload script
					queueSizeLimit  : 999, // The maximum size of the file queue
					simUploadLimit  : 1, // The number of simultaneous uploads allowed
					queueID         : false, // The optional ID of the queue container
					displayData     : null, // Set to "speed" to show the upload speed in the default queue item
					removeCompleted : true, // Set to true if you want the queue items to be removed when a file is done uploading
					onInit          : function() {}, // Function to run when uploadify is initialized
					onSelect        : function() {}, // Function to run when a file is selected
					onSelectOnce    : function() {}, // Function to run once when files are added to the queue
					onQueueFull     : function() {}, // Function to run when the queue reaches capacity
					onCheck         : function() {}, // Function to run when script checks for duplicate files on the server
					onCancel        : function() {}, // Function to run when an item is cleared from the queue
					onClearQueue    : function() {}, // Function to run when the queue is manually cleared
					onError         : function() {}, // Function to run when an upload item returns an error
					onProgress      : function() {}, // Function to run each time the upload progress is updated
					onComplete      : function() {}, // Function to run when an upload is completed
					onAllComplete   : function() {}  // Function to run when all uploads are completed
				}, options);
				$(this).data('settings',settings);
				var pagePath = location.pathname;
				pagePath = pagePath.split('/');
				pagePath.pop();
				pagePath = pagePath.join('/') + '/';
				var data = {};
				data.uploadifyID = settings.id;
				data.pagepath = pagePath;
				if (settings.buttonImg) data.buttonImg = escape(settings.buttonImg);
				if (settings.buttonText) data.buttonText = escape(settings.buttonText);
				if (settings.rollover) data.rollover = true;
				data.script = settings.script;
				data.folder = escape(settings.folder);
				if (settings.scriptData) {
					var scriptDataString = '';
					for (var name in settings.scriptData) {
						scriptDataString += '&' + name + '=' + settings.scriptData[name];
					}
					data.scriptData = escape(scriptDataString.substr(1));
				}
				data.width          = settings.width;
				data.css            = settings.css;
				data.height         = settings.height;
				data.wmode          = settings.wmode;
				data.method         = settings.method;
				data.queueSizeLimit = settings.queueSizeLimit;
				data.simUploadLimit = settings.simUploadLimit;
				if (settings.hideButton)   data.hideButton   = true;
				if (settings.fileDesc)     data.fileDesc     = settings.fileDesc;
				if (settings.fileExt)      data.fileExt      = settings.fileExt;
				if (settings.multi)        data.multi        = true;
				if (settings.auto)         data.auto         = true;
				if (settings.sizeLimit)    data.sizeLimit    = settings.sizeLimit;
				if (settings.sizeMin)      data.sizeMin      = settings.sizeMin;
				if (settings.checkScript)  data.checkScript  = settings.checkScript;
				if (settings.fileDataName) data.fileDataName = settings.fileDataName;
				if (settings.queueID)      data.queueID      = settings.queueID;
				// install swf
				if (settings.onInit() !== false) {
					//$(this).css('display','none');
					//hide
					$(this).html('<div class="upload_round_box" id="upload_load_box"><div class="upload_round_box" id="upload_main_box"><div id="upload_info">uplaod_info</div><div id="upload_uploader"></div></div></div>');
					$('#upload_uploader').append('<div id="' + $(this).attr('id') + 'Uploader"></div>');
					swfobject.embedSWF(settings.uploader, settings.id + 'Uploader', settings.width, settings.height, '9.0.24', settings.expressInstall, data, {'quality':'high','wmode':settings.wmode,'allowScriptAccess':settings.scriptAccess},{},function(event) {
						if (typeof(settings.onSWFReady) == 'function' && event.success) settings.onSWFReady();
					});
						var byteSize = Math.round(data.sizeLimit / 1024 * 100) * .01;
						var suffix = 'KB';
						if (byteSize > 1024) {
							byteSize = Math.round(byteSize *.001 * 100) * .01;
							suffix = 'MB';
						}
						var sizeParts = byteSize.toString().split('.');
						if (sizeParts.length > 1) {
							byteSize = sizeParts[0] + '.' + sizeParts[1].substr(0,2);
						} else {
							byteSize = sizeParts[0];
						}						
					$('#upload_info').html('您可以上传不超过'+byteSize+suffix+'大小的文件,一次可上传'+data.queueSizeLimit+'个。');
				}
				if (typeof(settings.onOpen) == 'function') {
					$(this).bind("uploadifyOpen", settings.onOpen);
				}
				$(this).bind("uploadifySelect", {'action': settings.onSelect, 'queueID': settings.queueID}, function(event, ID, fileObj) {
					if (event.data.action(event, ID, fileObj) !== false) {
						var byteSize = Math.round(fileObj.size / 1024 * 100) * .01;
						var suffix = 'KB';
						if (byteSize > 1000) {
							byteSize = Math.round(byteSize *.001 * 100) * .01;
							suffix = 'MB';
						}
						var sizeParts = byteSize.toString().split('.');
						if (sizeParts.length > 1) {
							byteSize = sizeParts[0] + '.' + sizeParts[1].substr(0,2);
						} else {
							byteSize = sizeParts[0];
						}
						if (fileObj.name.length > 15) {
							fileName = fileObj.name.substr(0,15) + '...';
						} else {
							fileName = fileObj.name;
						}
						$('#upload_load_box').append('<div id="' + $(this).attr('id') + ID + '" class="upload_team_class">\
								<div class="upload_box_cancel">\
									<a href="javascript:$(\'#' + $(this).attr('id') + '\').uploadifyCancel(\'' + ID + '\')"><img src="' + settings.cancelImg + '" border="0" /></a>\
								</div>\
								<span class="fileName">' + fileName + ' (' + byteSize + suffix + ')</span><span class="percentage"></span>\
								<div class="upload_progress_box">\
									<div id="' + $(this).attr('id') + ID + 'ProgressBar" class="upload_progress_bar"></div>\
								</div>\
							</div>');
						$('#'+ $(this).attr('id') + ID).hide();
						$('#'+ $(this).attr('id') + ID).slideDown("slow");
					}
				});
				$(this).bind("uploadifySelectOnce", {'action': settings.onSelectOnce}, function(event, data) {
					event.data.action(event, data);
					if (settings.auto) {
						if (settings.checkScript) { 
							$(this).uploadifyUpload(null, false);
						} else {
							$(this).uploadifyUpload(null, true);
						}
					}
				});
				$(this).bind("uploadifyQueueFull", {'action': settings.onQueueFull}, function(event, queueSizeLimit) {
					if (event.data.action(event, queueSizeLimit) !== false) {
						$('#upload_info').html('队列文件已满，队列文件大小超过 ' + queueSizeLimit );
					}
				});
				$(this).bind("uploadifyCheckExist", {'action': settings.onCheck}, function(event, checkScript, fileQueueObj, folder, single) {
					var postData = new Object();
					postData = fileQueueObj;
					postData.folder = (folder.substr(0,1) == '/') ? folder : pagePath + folder;
					if (single) {
						for (var ID in fileQueueObj) {
							var singleFileID = ID;
						}
					}
					$.post(checkScript, postData, function(data) {
						for(var key in data) {
							if (event.data.action(event, data, key) !== false) {
								var replaceFile = confirm("Do you want to replace the file " + data[key] + "?");
								if (!replaceFile) {
									document.getElementById($(event.target).attr('id') + 'Uploader').cancelFileUpload(key,true,true);
								}
							}
						}
						if (single) {
							document.getElementById($(event.target).attr('id') + 'Uploader').startFileUpload(singleFileID, true);
						} else {
							document.getElementById($(event.target).attr('id') + 'Uploader').startFileUpload(null, true);
						}
					}, "json");
				});
				$(this).bind("uploadifyCancel", {'action': settings.onCancel}, function(event, ID, fileObj, data, remove, clearFast) {
					if (event.data.action(event, ID, fileObj, data, clearFast) !== false) {
						if (remove) { 
							var fadeSpeed = (clearFast == true) ? 0 : 250;
							$("#" + $(this).attr('id') + ID).fadeOut(fadeSpeed, function() { $(this).remove() });
							$('#upload_info').html(fileObj.name + '的上传被关闭！' );
						}
					}
				});
				$(this).bind("uploadifyClearQueue", {'action': settings.onClearQueue}, function(event, clearFast) {
					var queueID = (settings.queueID) ? settings.queueID : $(this).attr('id') + 'Queue';
					if (clearFast) {
						$("#" + queueID).find('.upload_team_class').remove();
					}
					if (event.data.action(event, clearFast) !== false) {
						$("#" + queueID).find('.upload_team_class').each(function() {
							var index = $('.upload_team_class').index(this);
							$(this).delay(index * 100).fadeOut(250, function() { $(this).remove() });
						});
					}
				});
				var errorArray = [];
				$(this).bind("uploadifyError", {'action': settings.onError}, function(event, ID, fileObj, errorObj) {
					if (event.data.action(event, ID, fileObj, errorObj) !== false) {
						var fileArray = new Array(ID, fileObj, errorObj);
						errorArray.push(fileArray);
						$("#upload_info").html("发生错误=>" + errorObj.type +'：'+errorObj.info);
						$("#" + $(this).attr('id') + ID).find('.upload_progress_box').hide();
					}
				});
				if (typeof(settings.onUpload) == 'function') {
					$(this).bind("uploadifyUpload", settings.onUpload);
				}
				$(this).bind("uploadifyProgress", {'action': settings.onProgress, 'toDisplay': settings.displayData}, function(event, ID, fileObj, data) {
					if (event.data.action(event, ID, fileObj, data) !== false) {
						if (fileObj.name.length > 15) {
							fileObj.name = fileObj.name.substr(0,15) + '...';
						}
						$("#" + $(this).attr('id') + ID + "ProgressBar").animate({'width': data.percentage + '%'},250,function() {
							if (data.percentage == 100) {
								$(this).closest('.upload_progress_box').fadeOut(250,function() {$(this).remove()});
							}
						});
						if (event.data.toDisplay == 'percentage') displayData = ' - 上传进度：' + data.percentage + '%';
						if (event.data.toDisplay == 'speed') displayData = ' - 上传速度：' + data.speed + 'KB/s';
						if (event.data.toDisplay == null) displayData = ' - 上传速度：' + data.speed + 'KB/s - 上传进度：' + data.percentage + '%';
						$("#upload_info").html('正在上传文件'+displayData);
					}
				});
				$(this).bind("uploadifyComplete", {'action': settings.onComplete}, function(event, ID, fileObj, response, data) {
					if (event.data.action(event, ID, fileObj, response, data) !== false) {
						if (fileObj.name.length > 15) {
							fileObj.name = fileObj.name.substr(0,15) + '...';
						}
						if (settings.removeCompleted) {
							$("#" + $(this).attr('id') + ID).fadeOut(250,function() {$(this).remove()});
							$('#upload_info').html("上传完成：" + fileObj.name);
						}
						$("#" + $(event.target).attr('id') + ID).append('<div id="' + $(event.target).attr('id') + ID +'upload_complete" class="upload_team_complete"></div>');
						$('#upload_info').html("正在处理：" + fileObj.name+' 请稍等...');
						$("#" + $(event.target).attr('id') + ID +'upload_complete').html(unescape(response));
						$('#upload_info').html("上传完成：" + fileObj.name);
					}
				});
				if (typeof(settings.onAllComplete) == 'function') {
					$(this).bind("uploadifyAllComplete", {'action': settings.onAllComplete}, function(event, data) {
						if (event.data.action(event, data) !== false) {
							errorArray = [];
						}
					});
				}
			});
		},
		uploadifySettings:function(settingName, settingValue, resetObject) {
			var returnValue = false;
			$(this).each(function() {
				if (settingName == 'scriptData' && settingValue != null) {
					if (resetObject) {
						var scriptData = settingValue;
					} else {
						var scriptData = $.extend($(this).data('settings').scriptData, settingValue);
					}
					var scriptDataString = '';
					for (var name in scriptData) {
						scriptDataString += '&' + name + '=' + scriptData[name];
					}
					settingValue = escape(scriptDataString.substr(1));
				}
				returnValue = document.getElementById($(this).attr('id') + 'Uploader').updateSettings(settingName, settingValue);
			});
			if (settingValue == null) {
				if (settingName == 'scriptData') {
					var returnSplit = unescape(returnValue).split('&');
					var returnObj   = new Object();
					for (var i = 0; i < returnSplit.length; i++) {
						var iSplit = returnSplit[i].split('=');
						returnObj[iSplit[0]] = iSplit[1];
					}
					returnValue = returnObj;
				}
			}
			return returnValue;
		},
		uploadifyUpload:function(ID,checkComplete) {
			$(this).each(function() {
				if (!checkComplete) checkComplete = false;
				document.getElementById($(this).attr('id') + 'Uploader').startFileUpload(ID, checkComplete);
			});
		},
		uploadifyCancel:function(ID) {
			$(this).each(function() {
				document.getElementById($(this).attr('id') + 'Uploader').cancelFileUpload(ID, true, true, false);
			});
		},
		uploadifyClearQueue:function() {
			$(this).each(function() {
				document.getElementById($(this).attr('id') + 'Uploader').clearFileUploadQueue(false);
			});
		}
	})
})($);