import JQS from './jqs.js';
import typeahead from "typeahead.js"; 
import Bloodhound from "bloodhound-js";

/**
 * Search filter
 */
export default class searchFilter extends JQS{
	constructor(){
		// instantiate the parent class
		super();
		// set which methods should run on document ready
		this.loadScriptsReady = [this.prepareNames.name, this.doSearch.name];
		// array of all the searchable names
		this.names = [];

	}
	/**
	 * prepareNames prepare the array of searchable names for the typeahead library
	 * @return {void} 
	 */
	prepareNames(){
		$('.listing-card').each((i) => {
			// for each image, grabs its data attribute and pass the name field to the array of searchable names
			const data = $(`.listing-card:eq(${i})`).data('imageinfo');
			this.names.push(data.name);
		});
	}
	/**
	 * doSearch method that handles the searching
	 * @return {void} 
	 */
	doSearch(){
			// initialize bloodhound (dependency for typeahead)
			const engine = new Bloodhound({
				local : this.names,
				queryTokenizer: Bloodhound.tokenizers.whitespace,
	  			datumTokenizer: Bloodhound.tokenizers.whitespace
			});

			// initialize typeahead
			$('.search__input').typeahead({
					hint: true,
			        highlight: true,
			        minLength: 1 
		    	},
		    	{
		    		name : 'names',
		    		source: engine
		    	}
			);
			// on search submit grab the value and toggle the images by name
			$('#search__submit').click((e) => {
				const query = $('.search__input[name="filter-search"]').val();
				if (query.length > 0) {
					this.toggle('name', query);
				}
				else{
					this.reset();
				}
			});	

			$('.search__input[name="filter-search"]').on('keydown', (e) => {
				if (e.keyCode === 13) {
					const query = $('.search__input[name="filter-search"]').val();
					if (query.length > 0) {
						this.toggle('name', query);
					}
					else{
						this.reset();
					}		
				}
			});

			// on tag submit grab the checked boxes and their values (the tag ids)
			// and then toggle the images by tag id
			$('#tag__submit').click((e) => {
				const query = $('.tag__input[name="tag-options"]:checked');
				const tagIds = ['...'];
				if (query.length > 0) {
					$.each(query, (index, elem) => {
						const tagId = $(elem).val();
						tagIds.push(tagId);
					});
					this.toggle('tags', tagIds);
				}
				else{
					this.reset();
				}
			});
	}
	/**
	 * [toggle toggle the images visibility by name search or by tags
	 * @param  {string} by    which data attribute field to check against
	 * @param  {string | number | array} value the value to check against
	 * @return {void} 
	 */
	toggle(by, value){
		// loop through all the images 
		$('.listing-card').each((i) => {
			// we need to check if we are checking against a singular value or an object of values
			switch(typeof $(`.listing-card:eq(${i})`).data('imageinfo')[`${by}`]){
				case 'string':
				case 'number':
					// when the data attribute is single value then we can assume we are checking against a single value
					if ($(`.listing-card:eq(${i})`).data('imageinfo')[`${by}`] === value) {
						$(`.listing-card:eq(${i})`).show();
					}
					else{
						$(`.listing-card:eq(${i})`).hide();
					}
					break;
				case 'object':
					// we take the data attribute (as an array) and the value (which is also an array)
					// then transform them into a string and check if the value is within the data attribute
					let tags = Object.keys($(`.listing-card:eq(${i})`).data('imageinfo')[`${by}`]);
					let contains = value.reduce(function(acc, currentVal){
						return acc !== true ? tags.includes(currentVal) : acc;
					});

					if (contains) {
						$(`.listing-card:eq(${i})`).show();
					}
					else{
						$(`.listing-card:eq(${i})`).hide();
					}
					
					break;
			}
		});


	}
	/**
	 * reset show all the images
	 * @return {void}
	 */
	reset(){
		$('.listing-card').show();
	}


}