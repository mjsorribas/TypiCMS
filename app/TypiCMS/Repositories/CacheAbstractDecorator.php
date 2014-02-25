<?php namespace TypiCMS\Repositories;

use App;

abstract class CacheAbstractDecorator {

	protected $repo;
	protected $cache;

	public function view()
	{
		return $this->repo->view();
	}

	public function route()
	{
		return $this->repo->route();
	}

	public function getModel()
	{
		return $this->repo->getModel();
	}

	/**
	 * Retrieve model by id
	 * regardless of status
	 *
	 * @param  int $id model ID
	 * @return stdObject object of model information
	 */
	public function byId($id)
	{
		// Build the cache key, unique per model slug
		$key = md5(App::getLocale().'id.'.$id);

		if ( $this->cache->has($key) ) {
			return $this->cache->get($key);
		}

		// Item not cached, retrieve it
		$model = $this->repo->byId($id);

		$this->cache->put($key, $model);

		return $model;
	}


	/**
	 * Get paginated pages
	 *
	 * @param int $paginationPage Number of pages per page
	 * @param int $limit Results per page
	 * @param boolean $all Show published or all
	 * @return StdClass Object with $items and $totalItems for pagination
	 */
	public function byPage($paginationPage = 1, $limit = 10, $all = false, $relatedModel = null)
	{
		$models = $this->repo->byPage($paginationPage, $limit, $all, $relatedModel);
		return $models;
	}


	/**
	 * Get all models
	 *
	 * @param boolean $all Show published or all
     * @return StdClass Object with $items
	 */
	public function getAll($all = false, $relatedModel = null)
	{
		// Build our cache item key, unique per model number,
		// limit and if we're showing all
		$allkey = ($all) ? '.all' : '';
		$key = md5(App::getLocale().'all'.$allkey);

		if ( $this->cache->has($key) ) {
			return $this->cache->get($key);
		}

		// Item not cached, retrieve it
		$models = $this->repo->getAll($all, $relatedModel);

		// Store in cache for next request
		$this->cache->put($key, $models);

		return $models;
	}


	/**
	 * Get single model by URL
	 *
	 * @param string  URL slug of model
	 * @return object object of model information
	 */
	public function bySlug($slug)
	{
		// Build the cache key, unique per model slug
		$key = md5(App::getLocale().'slug.'.$slug);

		if ( $this->cache->has($key) ) {
			return $this->cache->get($key);
		}

		// Item not cached, retrieve it
		$model = $this->repo->bySlug($slug);

		// Store in cache for next request
		$this->cache->put($key, $model);

		return $model;

	}


	/**
	 * Create a new model
	 *
	 * @param array  Data to create a new object
	 * @return boolean
	 */
	public function create(array $data)
	{
		return $this->repo->create($data);
	}


	/**
	 * Update an existing model
	 *
	 * @param array  Data to update a model
	 * @return boolean
	 */
	public function update(array $data)
	{
		return $this->repo->update($data);
	}

    /**
     * Make a string "slug-friendly" for URLs
     * @param  string $string  Human-friendly tag
     * @return string       Computer-friendly tag
     */
  //   protected function slug($string)
  //   {
		// return $this->repo->slug($string);
  //   }


	/**
	 * Get total model count
	 *
	 * @return int  Total models
	 */
	// protected function total($all = false)
	// {
	// 	if ( ! $all ) {
	// 		return $this->repo->where('status', 1)->count();
	// 	}

	// 	return $this->repo->count();
	// }


	/**
	 * Sort models
	 *
	 * @param array  Data to update Pages
	 * @return boolean
	 */
	public function sort(array $data)
	{
		return $this->repo->sort($data);
	}


	public function getModulesForSelect()
	{
		return $this->repo->getModulesForSelect();
	}


	public function delete($model)
	{
		return $this->delete($model);
	}


}