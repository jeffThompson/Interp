
/* LOAD AND DISPLAY STL FILE
Jeff Thompson | 2013 | www.jeffreythompson.org

Requires three.js and jquery; setup variables are located in <head> of HTML file
*/

// other variables
var model;
var container, stats;
var camera, cameraTarget, controls, scene, renderer;

// setup and go!
if (!Detector.webgl) Detector.addGetWebGLMessage();
init();
animate();

// load and setup everything
function init() {
	container = document.createElement('div');
	document.body.appendChild(container);
	camera = new THREE.PerspectiveCamera(60, window.innerWidth/window.innerHeight, 0.1, 100000);	// 0.1/100k = almost no clipping
	camera.position.z = 50;

	// controls
	controls = new THREE.TrackballControls(camera);
	controls.rotateSpeed = 1.0;
	controls.zoomSpeed = 1.2;
	controls.panSpeed = 0.8;
	controls.noZoom = false;
	controls.noPan = false;
	controls.staticMoving = true;
	controls.dynamicDampingFactor = 0.3;
	// controls.keys = [ 65, 83, 68 ];
	controls.addEventListener('change', render);

	// create scene
	scene = new THREE.Scene();

	// load file (pass filename as argument
	loadFile(modelFilenames[whichModel]);

	// lights
	scene.add(new THREE.AmbientLight(0x777777));		// basic light
	addShadowedLight(-1, 1, 1, 0xffffff, 1.0);			// two incidental ones
	addShadowedLight(0.5, 1, -1, 0xffffff, 1.0);

	// renderer
	renderer = new THREE.WebGLRenderer( { antialias: true, alpha: false } );
	renderer.setSize(window.innerWidth, window.innerHeight);
	renderer.setClearColor(backgroundColor, 1);
	renderer.gammaInput = true;
	renderer.gammaOutput = true;
	renderer.physicallyBasedShading = true;
	renderer.shadowMapEnabled = true;
	renderer.shadowMapCullFace = THREE.CullFaceBack;
	container.appendChild(renderer.domElement);

	// stats
	if (statistics) {
		stats = new Stats();
		stats.domElement.style.position = 'absolute';
		stats.domElement.style.top = '0px';
		container.appendChild(stats.domElement);
	}

	// watch for changes
	window.addEventListener('resize', onWindowResize, false);
}

// load file (encoded as ASCII format STL), pass filename (not full path) as arg
function loadFile(filename) {
	//$('#loadingAnimation').show();						// show loading animation
	var loader = new THREE.STLLoader();
	loader.addEventListener('load', function(event) {
		var geometry = event.content;
		var material = new THREE.MeshPhongMaterial( { ambient: objectShadowColor, color: objectColor, specular: objectReflectionColor, shininess: 50 } );
		model = new THREE.Mesh(geometry, material);
		model.scale.set(scale, scale, scale);				// scale to fit
		THREE.GeometryUtils.center(geometry);				// rotate around center (http://stackoverflow.com/a/13587723/1167783)
		model.castShadow = true;
		model.receiveShadow = true;
		scene.add(model);
	});
	loader.load('models/' + filename);		// load it!
	
	//$('#loadingAnimation').hide();																						// when done, hide the animation
	$('#modelFilename').text(filename.toUpperCase());														// change filename
	$('#download').attr('href', 'models/' + filename);													// download link
	$('#download').text('download (' + filesJSON[whichModel].filesize + ')');		// show file size in download link
	$('#permalink').attr('href', 'gallery.php#' + filename);										// change permalink
}

// add lights
function addShadowedLight(x, y, z, color, intensity) {
	var directionalLight = new THREE.DirectionalLight(color, intensity);
	directionalLight.position.set(x, y, z);
	scene.add(directionalLight);
	directionalLight.castShadow = true;

	var d = 1;
	directionalLight.shadowCameraLeft = -d;
	directionalLight.shadowCameraRight = d;
	directionalLight.shadowCameraTop = d;
	directionalLight.shadowCameraBottom = -d;

	directionalLight.shadowCameraNear = 1;
	directionalLight.shadowCameraFar = 4;

	directionalLight.shadowMapWidth = 1024;
	directionalLight.shadowMapHeight = 1024;

	directionalLight.shadowBias = -0.005;
	directionalLight.shadowDarkness = 0.15;
}

// update when window changes size
function onWindowResize() {
	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();
	renderer.setSize(window.innerWidth, window.innerHeight);
	controls.handleResize();
	render();
}

// update every frame
function animate() {
	requestAnimationFrame(animate);
	controls.update();
	render();								// redraw every frame (remove to only draw when interacted with)
}

// render frame
function render() {
	if (rotateModel) {
		model.rotation.x += rotSpeed;		// rotate model
		model.rotation.y += rotSpeed;
	}
	renderer.render(scene, camera);			// render scene
	if (statistics) stats.update();			// update stats
}

// toggle model rotation
function toggleModelRotation() {
	if (rotateModel) {
		rotateModel = false;
		$('#rotate').text('rotate');
	}
	else {
		rotateModel = true;
		$('#rotate').text('pause');
	}
}

// new model when arrow keys are pressed
function nextModel() {
	scene.remove(model);																				// remove previous model from scene
	whichModel += 1;																						// increment which model to load
	if (whichModel == modelFilenames.length) whichModel = 0;		// if past length of list, wrap around to 0
	loadFile(modelFilenames[whichModel]);												// load from list of filenames
	
	if (rotateOnNewModel) {
		rotateModel = true;																				// if specified, reset rotation to true
		$('#rotate').text('pause');
	}
}
function prevModel() {
	scene.remove(model);
	whichModel -= 1;
	if (whichModel < 0) whichModel = modelFilenames.length - 1;
	loadFile(modelFilenames[whichModel]);
	
	if (rotateOnNewModel) {
		rotateModel = true;
		$('#rotate').text('pause');
	}
}
