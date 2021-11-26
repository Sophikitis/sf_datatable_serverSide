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
// import './styles/app.css';
// import './styles/custom.scss';

// start the Stimulus application
import './bootstrap';

import '@tabler/core/dist/js/tabler.min'
import '@tabler/core/dist/css/tabler.min.css'

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');


import 'datatables.net/js/jquery.dataTables.min'
import 'datatables.net-dt/css/jquery.dataTables.min.css'

$(document).ready( function () {

    let table =  $('#table_id');
    let dtUrlData = table.first().data('urlData');


    var dt = table.DataTable(
        {
            autoWidth: false,
            serverSide: true,
            processing: false,
            search: {return: true},
            order: [[1, 'asc']],
            paging : true,
            info : true,
            searching: true,
            responsive: true,
            ajax: {
                url: dtUrlData,
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
                    orderable: false,
                    searchable: false,
                    targets: 4,
                    data: 'id',
                    render: function(data, type, full, meta){
                        let token = table.first().data('deleteToken');
                        let showUrl = table.first().data('show')  + data;
                        let tokenUrl = table.first().data('delete') + data;

                        if(type === 'display'){
                            // content
                            data =
                                '<div class="btn-list justify-content-end">'+
                                '<a class="btn btn-white btn-square" href="/'+showUrl+'">show</a>'+
                                '<button id="token_delete" class="btn btn-white btn-square" data-method="DELETE" data-token='+token+' data-url="/'+tokenUrl+'">' +
                                'delete' +
                                '</button>' +
                                '</div>'
                        }

                        return data;
                    },

                },



            ],
        }
    );



    /*DELETE ACTION
    *
    * ______________________________________________________________*/
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
                dt
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




    // todo : comprendre ca
    table.on('requestChild.dt', function(e, row) {
        console.log('click here => requestChild');
        row.child(format(row.data())).show();
    })

    // ______________________________________________________



    /*DETAIL ACTION
    *
    * ______________________*/
    table.on('click', 'tbody td.dt-control', function (el, data) {
        const tr = $(this).closest('tr');
        const row = dt.row(tr);
        let urlDetail = table.first().data('detail');

        $.ajax({
            url: urlDetail,
            method: 'POST',
            data: { id: row.data().id}
        }).done(function(data) {
            if ( row.child.isShown() ) {
                row.child.hide();
            }else{
                row.child( format(data) ).show();
            }
        }).fail(function(){
            console.log('error')
        });
    } );



    function format ( d ) {

        if (d.tags.length === 0){
            return "data not found"
        }

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


} )