/**
 * jQuery Yii plugin file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: jquery.yii.js 252 2008-11-20 19:02:02Z qiang.xue $
 */

;(function($) {

$.yii = {
	version : '1.0',

	submitForm : function (element, url, params) {
		var f = $(element).parents('form')[0];
		if (!f) {
			f = document.createElement('form');
			f.style.display = 'none';
			element.parentNode.appendChild(f);
			f.method = 'POST';
		};
		if (typeof url == 'string' && url != '') {
			f.action = url;
		};
		jQuery.each(params, function(name, value) {
			var input = document.createElement("input");
			input.setAttribute("type", "hidden");
			input.setAttribute("name", name);
			input.setAttribute("value", value);
			f.appendChild(input);
		});

		f.submit();
	}
};

})(jQuery);
