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

import '@tabler/core/dist/js/tabler.min'
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
            autoWidth: false,
            processing: false,
            search: {
                return: true

            },
            order: [[1, 'asc']],
            serverSide: true,
            ajax: {
                url: 'http://127.0.0.1:8000/data',
                method: 'POST'
            },
            columns: [
                {
                    className:      'dt-control',
                    orderable:      false,
                    data:           null,
                    defaultContent: '',
                },
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
                    targets: 1,
                    visible:false,
                    title: 'ID',
                    searchable: false
                },
                {
                    name: "firstname",
                    targets: 2,
                    visible:true,
                    title: 'firstname',
                    searchable: false,
                    orderable: false
                },
                {
                    name: "lastname",
                    targets: 3,
                    visible:true,
                    title: 'lastname',
                    searchable: true
                },
                {
                    searchable: false,
                    targets: 4,
                    data: 'id',
                    render: function(data, type, full, meta){
                        let token = $('#table_id').first().data('deleteToken');

                        if(type === 'display'){
                            // content
                            data =
                                '<div class="btn-list justify-content-end">'+
                                '<a class="btn btn-white btn-square" href="'+Routing.generate('candidate_show',{ id: data })+'">show</a>'+
                                '<button id="token_delete" class="btn btn-white btn-square" data-method="DELETE" data-token='+token+' data-url="'+Routing.generate('candidate_delete',{ id: data })+'">' +
                                'delete' +
                                '</button>' +
                                '<button id="token_detail" class="btn btn-white btn-square" data-url="#">Details</button>'+
                                '</div>'
                        }

                        return data;
                    }
                },


            ],
        }
    );


    $('#table_id tbody').on( 'click', '#token_delete', function () {
        let element = $(this).parents('tr');
        let url = this.dataset.url
        let method = this.dataset.method
        let data= this.dataset.token

        const modal = new Promise(function(resolve, reject){
            $('#modal-danger').modal('show');
            $('#modal-danger .btn-action-agree').click(function(){
                resolve("user clicked");
            });
            $('#modal-danger .btn-action-cancel').click(function(){
                reject("user clicked cancel");
            });
        }).then(function(val){
            $.ajax({
                url: url,
                method: method,
                data: { CSRF: data}
            }).done(function() {
                table
                    .row(element)
                    .remove()
                    .draw();
            }).fail(function(){
                console.log('error')
            });
        }).catch(function(err){
            //user clicked cancel
            console.log("user clicked cancel", err)
        });
    } );

    $('#table_id tbody').on( 'click', '#token_detail', function () {
        alert('coucou');
    } );



    // todo : comprendre ca
    $('#table_id').on('requestChild.dt', function(e, row) {
        console.log('click here => requestChild');
        row.child(format(row.data())).show();
    })

    $('#table_id').on('click', 'tbody td.dt-control', function (el, data) {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        // data-url="'+Routing.generate('candidate_detail_dt')

        // candidate_detail_dt
        $.ajax({
            url: Routing.generate('candidate_detail_dt'),
            method: 'POST',
            data: { id: row.data().id}
        }).done(function(data) {

            // debugger


            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
            }
            else {
                // Open this row
                row.child( format(data) ).show();
            }
        }).fail(function(){
            console.log('error')
        });





    } );

    function format ( d ) {
        let htmlContent = ''
        d.tags.forEach(el => {
            htmlContent +=
                '<tr>'+
                '<td>'+el.tag+'</td>'+
                '<td>'+el.value+'</td>'+
                '</tr>'
        })

        // `d` is the original data object for the row
        return '<table id="coquin" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            htmlContent+ '</table>';
    }


} );