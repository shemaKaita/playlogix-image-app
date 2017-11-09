<?php

$app->get('/[{name}]', function ($request, $response, $args) {
	// set the images and tags and pass them along to the view
	$images = new images();

	$args['tags'] = $images->tags;

	$args['images'] = $images->images;

    return $this->renderer->render($response, 'index.phtml', $args);

});
