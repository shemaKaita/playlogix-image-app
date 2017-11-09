/**
 * Class for all jquery scripts.
 */
class JQS{
	constructor(){
		this.loadScripts = [];
		this.loadScriptsReady = [];
		this.loadReady();
		this.load();
	}
	load(){
		$(window).on('load', () => {
			this.loadScripts.forEach((script, index) => {
				this[script]();
			});
		});
	}
	loadReady(){
		$(document).ready((e) => {
			this.loadScriptsReady.forEach((script, index) => {
				this[script]();
			});
		});
	}
}

export default JQS;