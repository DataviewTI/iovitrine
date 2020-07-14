"use strict";
let mix = require("laravel-mix");

function IOVitrine(params = {}) {
  // let $ = this;
  let dep = {
    vitrine: "node_modules/intranetone-vitrine/src/",
    sortable: "node_modules/sortablejs/",
    cropper: "node_modules/cropperjs/dist/",
    jquerycropper: "node_modules/jquery-cropper/dist/",
    dropzone: "node_modules/dropzone/dist/",
    moment: "node_modules/moment/",
  };

  // let config = {
  //   optimize: false,
  //   sass: false,
  //   fe: true,
  //   cb: () => {},
  // };

  this.compile = (IO, callback = () => {}) => {
    mix.styles(
      [
        IO.src.css + "helpers/dv-buttons.css",
        IO.src.io.css + "dropzone.css",
        IO.src.io.css + "dropzone-preview-template.css",
        IO.dep.io.toastr + "toastr.min.css",
        IO.src.io.css + "toastr.css",
        IO.src.io.css + "sortable.css",
        dep.cropper + "cropper.css",
        dep.vitrine + "dropzone.css",
        dep.vitrine + "vitrine.css",
        IO.dep.io.slimSelect + "slimselect.min.css",
      ],
      IO.dest.io.root + "services/io-vitrine.min.css"
    );

    mix.babel(
      [
        dep.sortable + "Sortable.min.js",
        IO.dep.io.toastr + "toastr.min.js",
        IO.src.io.js + "defaults/def-toastr.js",
        dep.dropzone + "dropzone.js",
        IO.src.io.js + "dropzone-loader.js",
        dep.cropper + "cropper.js",
        dep.jquerycropper + "jquery-cropper.js",
        IO.dep.io.slimSelect + "slimselect.min.js",
        dep.vitrine + "jquery.maskMoney.min.js",
        dep.vitrine + "vitrine.js",
        dep.vitrine + "formacao.js",
      ],
      IO.dest.io.root + "services/io-vitrine.min.js"
    );

    mix.babel(
      [
        IO.dep.jquery_mask + "jquery.mask.min.js",
        dep.moment + "min/moment.min.js",
        IO.src.io.vendors + "moment/moment-pt-br.js",
        IO.dep.jquery_mask + "jquery.mask.min.js",
        IO.src.js + "extensions/ext-jquery.mask.js",
      ],
      IO.dest.io.root + "services/io-vitrine-mix.min.js"
    );

    // //copy separated for compatibility
    // mix.scripts(
    //   $.dep.vitrine + "vitrine.js",
    //   IO.dest.io.root + "services/io-vitrine.min.js"
    // );

    //copy separated for compatibility
    // mix.scripts(
    //   dep.vitrine + "formacao.js",
    //   IO.dest.io.root + "services/io-vitrine-formacao.min.js"
    // );

    mix.copy(
      dep.vitrine + "optimized_cities.js",
      IO.dest.js + "optimized_cities.js"
    );

    mix.copyDirectory(
      dep.vitrine + "images",
      IO.dest.io.root + "images/vitrine"
    );

    callback(IO);
  };
}

module.exports = IOVitrine;
