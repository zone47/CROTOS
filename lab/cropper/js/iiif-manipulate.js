/**
 * Valid formats for IIIF images
 * @type {string[]}
 */
var formats = ["jpg", "tif", "png", "gif", "jp2", "pdf", "webp"];

/**
 * IIIFimg constructor.
 * @param url
 * @constructor
 */
var IIIFimg = function (url){

	// Private variables
	this._url = url;
	this._scheme= '';
	this._server='';
	this._prefix='';
	this._identifier='';
	this._region='full';
	this._size='full';
	this._rotation='0';
	this._quality='default';
	this._format='jpg';
	this._info='';

	// Components array looks like
	// {scheme}:, , {server}, {prefix} ... , {identifier, {region}, {size}, {rotation}, {quality}.{format}
	var components = url.split('/');
	this._scheme = components[0].slice(0,-1); // trim off colon
	this._server = components[2];
	// Prefix is from server to identifier, to account for slashes in prefix
	this._prefix = components.slice(3, components.length-5).join('/');
	this._identifier = components[components.length-5]
	this._region = components[components.length-4];
	this._size = components[components.length-3];
	this._rotation = components[components.length-2];
	this._quality = components[components.length-1].split('.')[0];
	this._format = components[components.length-1].split('.')[1];
};

IIIFimg.prototype = (function() {
	var update = function(img) {
		img._info = img._scheme+"://"+[img._server, img._prefix, img._identifier, "info.json"].join("/");
		img._url = img._scheme+"://"+[img._server, img._prefix, img._identifier, img._region, img._size,
				img._rotation, img._quality + "." + img._format].join("/");
	};

	return {
		constructor:IIIFimg,

		getScheme:function () { return this._scheme; },
		getServer:function () { return this._server; },
		getPrefix:function () { return this._prefix; },
		getIdentifier:function () { return this._identifier; },
		getRegion:function () { return this._region; },
		getSize:function () { return this._size; },
		getRotation:function () { return this._rotation; },
		getQuality:function () { return this._quality; },
		getFormat:function () { return this._format; },
		getInfo:function () { return this._info; },
		getX:function() {
			var region = this.getRegion();
			var split = region.split(',');
			return split[0];
		},
		getY:function() {
			var region = this.getRegion();
			var split = region.split(',');
			return split[1];
		},
		getWidth:function() {
			var region = this.getRegion();
			var split = region.split(',');
			return split[2];
		},
		getHeight:function() {
			var region = this.getRegion();
			var split = region.split(',');
			return split[3];
		},

		/**
		 * Set a new region.
		 * If no parameters given, default value of "full" is applied.
		 *
		 * @param x - X coordinate of region
		 * @param y - Y coordinate of region
		 * @param w - Width of region
		 * @param h - Height of region
		 * @returns updated image URL
		 */
		setRegion:function (x, y, w, h) {
			var newRegion = [x, y, w, h].join(',') || "full";
			this._region = newRegion;
			update(this);
			return this._url;
		},
		/**
		 * Change the size of the image.
		 * If no parameters given, sets to default value "full"
		 *
		 * @param size (real) - Size of image.
		 * @param literal (bool) - Optional. If not "true", size is treated as a percentage
		 * (e.g. setSize(40, false) and setSize(40) both result in size "pct:40")
		 * @returns updated image URL
		 */
		setSize:function (size, literal) { // TODO support other kinds of size?
			if (literal)
				this._size = size;
			else
				this._size = ("pct:" + size) || "full";
			update(this);
			return this._url;
		},
		/**
		 * Change the image rotation and reflection.
		 * Not all servers support rotation. If no params given, or server does not support the rotation specified,
		 * default value of "0" is used.
		 *
		 * @param rot (real) -
		 * @param ref (bool) - Optional. If "true", reflects image.
		 * @returns updated image URL
		 */
		setRotation:function (rot, ref) {
			var newRotation = rot || "0";
			if (ref) newRotation = "!" + newRotation;
			this._rotation = newRotation;
			update(this);
			return this._url;
		},
		/**
		 *
		 *
		 * @param qual
		 * @returns updated image URL
		 */
		setQuality:function (qual) {
			var newQuality = qual || "default";
			this._quality = newQuality;
			update(this);
			return this._url;
		},
		/**
		 *
		 * @param format
		 * @returns updated image URL
		 */
		setFormat:function (format) {
			if (formats.indexOf(format) != -1)
				this._format = format;
			else this._format = formats[0];
			update(this);
			return this._url;
		}
	};
})();
