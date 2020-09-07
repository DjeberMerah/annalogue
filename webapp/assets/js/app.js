const $ = require('jquery');

require('bootstrap');

require('../css/global.scss');

$('input[type="file"]').change(function (e) {
    let name = e.target.files[0].name;

    $('.custom-file-label').html(name);
});
