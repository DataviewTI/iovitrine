'use strict';
let mix = require('laravel-mix');

function IOVitrine(params = {}) {
  let $ = this;
  this.dep = {
    vitrine: 'node_modules/intranetone-vitrine/src/',
    sortable: 'node_modules/sortablejs/',
    moment: 'node_modules/moment/',
    dropzone: 'node_modules/dropzone/dist/',
    cropper: 'node_modules/cropperjs/dist/',
    jquerycropper: 'node_modules/jquery-cropper/dist/'
  };

  let config = {
    optimize: false,
    sass: false,
    fe: true,
    cb: () => {}
  };

  this.compile = (IO, callback = () => {}) => {
    mix.styles(
      [
        // IO.src.io.root + 'custom/custom-devbridge.css',
        IO.src.css + 'helpers/dv-buttons.css',
        IO.dep.io.toastr + 'toastr.min.css',
        IO.src.io.css + 'toastr.css',
        $.dep.vitrine + 'vitrine.css',
        IO.src.io.css + 'dropzone.css',
        IO.src.io.css + 'dropzone-preview-template.css',
        $.dep.vitrine + 'dropzone.css',
        IO.src.io.css + 'sortable.css',
        $.dep.cropper + 'cropper.css'
      ],
      IO.dest.io.root + 'services/io-vitrine.min.css'
    );

    mix.babel(
      [IO.src.js + 'extensions/ext-jquery.js'],
      IO.dest.io.root + 'services/io-vitrine-mix-babel.min.js'
    );

    mix.babel(
      [
        $.dep.sortable + 'Sortable.min.js',
        IO.dep.io.toastr + 'toastr.min.js',
        IO.src.io.js + 'defaults/def-toastr.js',
        $.dep.dropzone + 'dropzone.js',
        IO.src.io.js + 'dropzone-loader.js',
        $.dep.vitrine + 'jquery.maskMoney.min.js'
      ],
      IO.dest.io.root + 'services/io-vitrine-babel.min.js'
    );

    mix.scripts(
      [
        IO.dep.jquery_mask + 'jquery.mask.min.js',
        IO.src.js + 'extensions/ext-jquery.mask.js',
        $.dep.cropper + 'cropper.js',
        $.dep.jquerycropper + 'jquery-cropper.js',
        $.dep.moment + 'min/moment.min.js',
        IO.src.io.vendors + 'moment/moment-pt-br.js'
      ],
      IO.dest.io.root + 'services/io-vitrine-mix.min.js'
    );

    //copy separated for compatibility
    mix.scripts(
      $.dep.vitrine + 'vitrine.js',
      IO.dest.io.root + 'services/io-vitrine.min.js'
    );

    //copy separated for compatibility
    mix.scripts(
      $.dep.vitrine + 'formacao.js',
      IO.dest.io.root + 'services/io-vitrine-formacao.min.js'
    );

    mix.copy(
      $.dep.vitrine + 'optimized_cities.js',
      IO.dest.js + 'optimized_cities.js'
    );

    mix.copyDirectory(
      $.dep.vitrine + 'images',
      IO.dest.io.root + 'images/vitrine'
    );

    // IO.__imgOptimize({
    //   from: $.dep.vitrine + 'vitrine',
    //   to: IO.dest.io.root + 'images/vitrine'
    // });

    callback(IO);
  };
}

module.exports = IOVitrine;
