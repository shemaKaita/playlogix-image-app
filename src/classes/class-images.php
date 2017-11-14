<?php

/**
 * Class for the images and their related data
 */
class images
{
    /**
     * $db the db connection
     * @var object
     */
    protected $db;

    /**
     * $images array for all the images
     * @var array
     */
    public $images = [];

    /**
     * $tags array for all the tags
     * @var array
     */
    public $tags = [];

    public function __construct()
    {
        // gain access to slim phps app container
        global $app;

        // get and set the db connection
        $this->db = $app->getContainer()->get('db');

        // set all the images
        $this->getAllImages();

        // set all the tags
        $this->getAllTags();

    }

    /**
     * getAllImages get all the images
     * @return array all the images and their data
     */
    public function getAllImages()
    {
        $sql = "
			SELECT A.*, B.tag_id, C.name tagName FROM images A
			LEFT JOIN tag_relationships B
			ON A.id = B.image_id
			LEFT JOIN tags C
			ON B.tag_id = C.id
			ORDER BY A.name ASC
		";

        $query = $this->db->prepare($sql);

        $query->execute();

        $result = $query->fetchAll();

        // index the images by id then add their tags
        foreach ($result as $image => $data) {
            if (isset($this->images[$data['id']])) {
                $this->images[$data['id']]['tags'][$data['tag_id']] = $data['tagName'];
            } else {
                $this->images[$data['id']] = [
                    'name' => $data['name'],
                    'url'  => $data['url'],
                    'tags' => [$data['tag_id'] => $data['tagName']],
                ];
            }
        }
        return $this->images;
    }

    /**
     * getAllTags get all the tags
     * @return array all the tags
     */
    public function getAllTags()
    {

        $sql = "
			SELECT * FROM tags
			ORDER BY name ASC
		";

        $query = $this->db->prepare($sql);

        $query->execute();

        $result = $query->fetchAll();

        foreach ($result as $tag) {

            $this->tags[$tag['id']] = $tag['name'];

        }

        return $this->tags;
    }

}
