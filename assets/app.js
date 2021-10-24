/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// require jQuery normally
const $ = require('jquery');
global.$ = global.jQuery = $;


// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import '@tabler/core'
import '@tabler/core/dist/css/tabler.min.css'


import 'datatables.net/js/jquery.dataTables.min'
import 'datatables.net-dt/css/jquery.dataTables.min.css'

const routes = require('../data/fos_js_routes.json');
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js';
Routing.setRoutingData(routes);

console.log(
    Routing.generate('candidate_show' ),
)




$(document).ready( function () {

    var table = $('#table_id').DataTable(
        {

            processing: false,
            search: {
                return: false

            },
            serverSide: true,
            ajax: {
                url: 'http://127.0.0.1:8000/data',
                method: 'POST'
            },
            columns: [
                { data: "id", },
                { data: "firstname"},
                { data: "lastname"}
            ],
            paging : true,
            info : true,
            searching: true,
            responsive: true,
            columnDefs: [
                {
                    name: "id",
                    targets: 0,
                    visible:false,
                    title: 'ID',
                    searchable: false
                },
                {
                    name: "firstname",
                    targets: 1,
                    visible:true,
                    title: 'firstname',
                    searchable: false
                },
                {
                    name: "lastname",
                    targets: 2,
                    visible:true,
                    title: 'lastname',
                    searchable: true
                },
                {
                    targets: 3,
                    data: 'id',
                    render: function(data, type, full, meta){
                        if(type === 'display'){
                            // content
                            data =
                                '<a href="'+Routing.generate('candidate_show',{ id: data })+'">show</a>'+
                                '<button data-url="'+Routing.generate('candidate_delete',{ id: data })+'">delete</button>'
                        }

                        return data;
                    }
                }

            ],
        }
    );

    $('#table_id tbody').on( 'click', 'button', function () {
        alert('TODO AJAX DELETE');
    } );


} );