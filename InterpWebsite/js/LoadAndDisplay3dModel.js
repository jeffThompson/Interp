
/*
INTERP
Jeff Thompson | 2013-14 | www.jeffreythompson.org

INTERP is a 2013 commission of New Radio and Performing Arts, Inc, for
its Turbulence.org website. It was made possible with funding from the
National Endowment for the Arts.

*/

var container, scene, camera, renderer, model, geometry, material, cube, controls;
var windowHalfX, windowHalfY;

function init() {
	
	// scene
	scene = new THREE.Scene();
	container = document.createElement('div');
	document.body.appendChild(container);

	// renderer
	renderer = new THREE.WebGLRenderer();
	renderer.setClearColor(0x222222);
	renderer.setSize(window.innerWidth, window.innerHeight);
	renderer.setFaceCulling(THREE.CullFaceNone);
	container.appendChild(renderer.domElement);

	// camera
	camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.001, 1000);

	// lights
	var ambient = new THREE.AmbientLight(0x333333);
	scene.add(ambient);

	var directionalLight = new THREE.DirectionalLight(0xffffff);
	directionalLight.position.set(0, 0, 100).normalize();
	directionalLight.castShadow = true;
	directionalLight.shadowDarkness = 0.5;
	scene.add(directionalLight);

	// controls
	controls = new THREE.TrackballControls(camera);
	controls.rotateSpeed = 1.0;
	controls.zoomSpeed = 1.2;
	controls.panSpeed = 0.8;
	controls.noZoom = false;
	controls.noPan = false;
	controls.staticMoving = true;
	controls.dynamicDampingFactor = 0.3;
	controls.addEventListener('change', render);

	// model (stored in a parent object so we can rotate)
	model = new THREE.Object3D();
	model.castShadow = true;
	model.receiveShadow = true;
	model.doubleSided = true;		// let us see back of faces (for 'inside out' models)
	scene.add(model);

	// load OBJ file
	var loader = new THREE.OBJMTLLoader();
	loader.load('models/' + modelName + '.obj', 'models/' + modelName + '.mtl', function(objFile) {

		model.add(objFile);

		// move camera to see object
		// via: http://stackoverflow.com/a/16940705/1167783
		var bs = objFile.children[0].geometry.boundingSphere;
		camera.position.z = (bs.radius * 1.5);

		// remove loading text when done
		var elem = document.getElementById('loadingAnimation');
		elem.parentNode.removeChild(elem);
	});

	// double-sided so we can see back of faces (solves most but not all problems)
	// via: http://stackoverflow.com/a/23641906/1167783
	if (model instanceof THREE.Object3D) {
    	model.traverse (function (mesh) {
        	if (! (mesh instanceof THREE.Mesh)) return;
			mesh.material.side = THREE.DoubleSide;
    	});
	}

	// handle mouse movement and resize
	document.addEventListener('mousemove', onDocumentMouseMove, false);
	window.addEventListener('resize', onWindowResize, false);
}

function onWindowResize() {
	windowHalfX = window.innerWidth / 2;
	windowHalfY = window.innerHeight / 2;
	
	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();
	renderer.setSize(window.innerWidth, window.innerHeight);
}

function onDocumentMouseMove(event) {
	mouseX = (event.clientX - windowHalfX) / 2;
	mouseY = (event.clientY - windowHalfY) / 2;
}

function animate() {
	// rotateCamera();
	requestAnimationFrame(animate);
	controls.update();
	render();
}

function render() { 
	model.rotation.x += rotSpeed;		// rotate model
	model.rotation.y += rotSpeed;
 	renderer.render(scene, camera);
}

function rotateCamera() {
	// via: http://mikeheavers.com/main/code-item/webgl_circular_camera_rotation_around_a_single_axis_in_threejs
	var x = camera.position.x;
	var y = camera.position.y;
	var z = camera.position.z;

	camera.position.x = x * Math.cos(rotSpeed) + z * Math.sin(rotSpeed);
	camera.position.z = z * Math.cos(rotSpeed) - x * Math.sin(rotSpeed);
	camera.lookAt(scene.position);
}
