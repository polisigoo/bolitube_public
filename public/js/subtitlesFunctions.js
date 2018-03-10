var SubSize = 18;

function addSubDelay (videoId, delay) { //Add delay to subtitle
	const video = document.getElementById(videoId);
	var txtTracks = player.textTracks();
	if (video) {
		Array.from(txtTracks).forEach((track) => {
			if (track.mode === 'showing') {
				Array.from(track.cues).forEach((cue) => {
					cue.startTime += delay || 0.5;
					cue.endTime += delay || 0.5;
				});
			}
		});
	}
}

function removeSubDelay (videoId, delay) { //Remove delay from subtitle
	const video = document.getElementById(videoId);
	var txtTracks = player.textTracks();
	if (video) {
		Array.from(txtTracks).forEach((track) => {
			if (track.mode === 'showing') {
				Array.from(track.cues).forEach((cue) => {
					cue.startTime -= delay || 0.5;
					cue.endTime -= delay || 0.5;
				});
			}
		});
	}
}

function moveSubRight (videoId, percent) { //Move the subtitle pos to the right
	const video = document.getElementById(videoId);
	var txtTracks = player.textTracks();
	if (video && verifyOffset()) {
		Array.from(txtTracks).forEach((track) => {
			if (track.mode === 'showing') {
				Array.from(track.cues).forEach((cue) => {
					if (!isNaN(cue.position)) {
						cue.position += percent || 5;
					} else {
						cue.position = 50 + percent;
					}
				});
			}
		});
	}
}

function verifyOffset(){
	var menu = document.getElementById('my-video').getBoundingClientRect();
	var sub = document.getElementsByClassName('vjs-text-track-display');
	if (sub) {
		sub = sub[0].children[0].children[0].children[0].getBoundingClientRect();

		if (sub.left - 80 > menu.left && sub.right + 80 < menu.right) {
			return true;
		}else{
			return false;
		}
	}			
}

function moveSubLeft (videoId, percent) { //Move the subtitle pos to the left
	const video = document.getElementById(videoId);
	var txtTracks = player.textTracks();

	if (video && verifyOffset()) {
		Array.from(txtTracks).forEach((track) => {
			if (track.mode === 'showing') {
				Array.from(track.cues).forEach((cue) => {
					if (!isNaN(cue.position)) {
						cue.position -= percent || 5;
					} else {
						cue.position = 50 - percent;
					}
				});
			}
		});
	}
}

function enlargeSubSize () {
	const css = document.createElement('style');
	css.type = 'text/css';
	css.innerHTML = `::cue { font-size: ${SubSize++}px; }`;
	document.body.appendChild(css);
}

function reduceSubSize () {
	const css = document.createElement('style');
	css.type = 'text/css';
	css.innerHTML = `::cue { font-size: ${SubSize--}px; }`;
	document.body.appendChild(css);
}

function setSubFontColor (fontColor) {
	const css = document.createElement('style');
	css.type = 'text/css';
	css.innerHTML = `::cue { color: ${fontColor}; }`;
	document.body.appendChild(css);
}

function addNewButton(data) {

	var myPlayer = data.player,
		controlBar,
		newElement = document.createElement('div'),
		newLink = document.createElement('a');

	newElement.id = data.id;
	newElement.className = data.id + ' vjs-control';

	newLink.innerHTML = "<i class='icon-" + data.icon + " line-height' aria-hidden='true'></i>";
	newLink.setAttribute('title', data.title);
	newElement.appendChild(newLink);
	controlBar = document.getElementsByClassName('vjs-control-bar')[0];
	insertBeforeNode = document.getElementsByClassName('vjs-fullscreen-control')[0];
	controlBar.insertBefore(newElement, insertBeforeNode);

	return newElement;
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function addCaptionButton(data) {

    var myPlayer = data.player,
        controlBar,
        captions,
        newElement = document.createElement('li'),
        newLink = document.createElement('span');

    newElement.id = data.id;
    newElement.className = data.id + ' vjs-menu-item';

    newLink.innerText = "add subtitle";
    newLink.className = 'vjs-menu-item-text';
    newLink.setAttribute('title', data.title);
    newElement.appendChild(newLink);
    controlBar = document.getElementsByClassName('vjs-control-bar')[0];
    menu = controlBar.getElementsByClassName('vjs-subs-caps-button')[0].getElementsByClassName('vjs-menu')[0].getElementsByClassName('vjs-menu-content')[0];
    insertBeforeNode = menu.getElementsByClassName('vjs-texttrack-settings')[0];
    insertAfter(newElement, insertBeforeNode);

    return newElement;
}