<?php
if ( !defined( 'ABSPATH' ) ) exit;



/***кнопка печати паспорта товара *******/

add_action ('woocommerce_after_add_to_cart_form', 'tpk_product_print_button');

function tpk_product_print_button() {
/*	add_action( 'wp_footer', 'tpk_print_button_script' );
	?>
<div class="tz-buttons"><a id="print-button" class="tzPrintBtn acf-button button button-primary button-large" href="javascript:void(0);"><span>...</span></a></div>
	<?php*/
}




/*** шорткод кнопки печати *******/

add_shortcode('print-button', 'tpk_all_print_button');

function tpk_all_print_button() {

	add_action( 'wp_footer', 'tpk_print_button_script' );
	
	?><a id="print-button" class="tzPrintBtn acf-button button button-primary button-large" href="javascript:void(0);"><span>...</span></a>
	<?php
}


function tpk_print_button_script() {

	?>
<script id="tpk_print_button_script">

document.addEventListener('DOMContentLoaded', function () {

	document.body.classList.add("body-loaded");
	document.body.addEventListener("click", clickButtons);


	function clickButtons(evt) {

    const from = evt.target;
    //console.clear();
    if (!from.classList.contains('tzPrintBtn')) { return; }

    evt.preventDefault;

		from.classList.add("printing");

		const iframe = document.createElement('iframe');

		iframe.id = 'print-frame';

		// Make it hidden
		// iframe.style.height = 0;
		iframe.style.visibility = 'hidden';
		//iframe.style.width = 0;

		iframe.setAttribute('srcdoc', '<html><body></body></html>');

		document.body.appendChild(iframe);


		iframe.contentWindow.addEventListener('afterprint', function () {

			iframe.parentNode.removeChild(iframe);
			

		});



		iframe.addEventListener('load', function () {

			let image = document.querySelectorAll('.woocommerce-product-gallery__image img')[0],
			gallery = document.querySelectorAll('ol.flex-control-thumbs')[0],
			title = document.querySelectorAll('h1.elementor-heading-title')[0],
			//content = document.getElementById("print-content"),
			contents = document.querySelectorAll('.print-content'),
			description = document.getElementById("tab-description"),
			logo = document.querySelectorAll('#masthead img[title="Лого"]')[0],
			qrdiv = document.createElement('div'),
			gallerydiv = document.createElement('div'),
			content = document.createElement('div');
			//qrcode = new Image();


if (contents.length > 0) {


    for (let i = 0; i < contents.length; i++) {
        
        content.appendChild(contents[i].cloneNode(true));

		content.id = 'print-content';

    } 

} else {

	content = '';

}


if (gallery) {

	gallery = gallery.cloneNode(true);

	gallerydiv.appendChild(gallery);

	gallerydiv.id = 'gallery';

} else {

	let gal = content.getElementsByTagName('img');

	if (gal.length > 0) {

		let img = gal[0];
		let galdiv = img.closest(".tz-info__section");

		//galdiv = galdiv.cloneNode(true);
		//console.log(gal);

		gallerydiv.appendChild(galdiv);

	gallerydiv.id = 'gallery';

	} else {
		//console.log('no image in content');
		gallerydiv = '';
	}

}

		
			qrdiv.innerHTML = '<img class="print-qrcode" width="150" height="150" src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&choe=UTF-8&chld=H&chl=' + window.location.href + '">';

			if(image){
				image = image.cloneNode(true);
				image.id = 'main-image';
			} else {

				let img = document.querySelectorAll('.main-print-img img');

				if ( img.length > 0 ) {

				image = img[0];
				image = image.cloneNode(true);
				image.id = 'main-image';

				} else {
					image = '';
				}
				
			}

			if(logo){logo = logo.cloneNode(true);}
			//if(gallery){gallery = gallery.cloneNode(true);}
			if(title){title = title.cloneNode(true);}
			if(content){content= content.cloneNode(true);}
			if(description){description = description.cloneNode(true);description.style.display = "block";}
			//gallery ??= '';
			title ??= '';
			//content ??= '';
			description ??= '';
			//image ??= '';

			const ibody = iframe.contentDocument.body;

			

	logo.id = 'print-logo';
	qrdiv.id = 'print-qr';



 ibody.innerHTML = ibody.innerHTML + '<style>.tz-info__section img,body{width:100%}body{display:grid;grid-template-columns:auto 2fr;grid-template-rows:repeat (5,auto);gap:0 30px;margin-right:30px;grid-template-areas:"qrcode logo" "title title" "img description" "img content" "gallery gallery";font-size:12px;line-height:1.2em}h1{font-size:18px}h3{font-size:16px}img{max-width:100%}img[title~=Лого]{grid-area:logo;margin:10px 30px 0 auto;width:100px;height:30px}h1.elementor-heading-title{grid-area:title;text-align:center}#tab-description{grid-area:description}#print-content{grid-area:content}#gallery{grid-area:gallery}#gallery .tz-info__section img{width: 130px;float:left;object-fit:cover;height: 130px;margin-right:4px;}#gallery ol{list-style:none;display:flex;flex-wrap:wrap;padding:0}#print-qr{grid-area:qrcode}#print-qr img{width:150px;height:150px;display:block}#main-image{grid-area:img;width:100%;max-width: 250px;height:auto}.tz-info__point-title {	float: left;margin-right: 10px;font-weight: bold;}.tz-info__section ul {padding: 0;list-style: inside;}</style>';



			ibody.append(logo, image, gallerydiv, title, description, content, qrdiv );

			let images = ibody.getElementsByTagName("img");

			Promise.all(Array.from(images).filter(img => !img.complete).map(img => new Promise(resolve => { img.onload = img.onerror = resolve; }))).then(() => {
    //console.log('images finished loading');
    from.classList.remove("printing");
    iframe.contentWindow.print();
    
});


	


		});

	}
});
 
</script>
  <?php
}

