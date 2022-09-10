<template>
  <section class="content">
        <!-- PDF Generator begins -->
        <html-pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="true"
            :paginate-elements-by-height="14000"
            filename="school_lists"
            :pdf-quality="2"
            :manual-pagination="true"
            pdf-format="a4"
            pdf-orientation="landscape"
            pdf-content-width="100%"
            ref="html2Pdf"
          >
              <section  id = "printPaper" slot="pdf-content" style=" width:100%; background-color: white;  padding: 0% 0.5% 40% 0.5%;">
                <div style = "margin-left: 0; width: 100%; ">
                  <h3 style="text-align:center; text-decoration: underline; padding: 1em; "> School Lists</h3>
                  <table class="table table-bordered" style="width: 100%; ">
                        <thead>
                            <tr>
                                                                                                                                                              <th>Name</th>
                                                                                                                                <th>Address</th>
                                                                                                                                <th>Phone No 1</th>
                                                                                                                                <th>Phone No 2</th>
                                                                                                                                <th>Email 1</th>
                                                                                                                                <th>Email 2</th>
                                                                                                                                                                                                                      </tr>   
                        </thead>
                        <tbody >
                            <tr v-for="(school, index) in schools" :key="school.id">
                                                                                                                                                                              <td>{{ school.name }} </td>
                                                                                                                                              <td>{{ school.address }} </td>
                                                                                                                                              <td>{{ school.phone_no_1 }} </td>
                                                                                                                                              <td>{{ school.phone_no_2 }} </td>
                                                                                                                                              <td>{{ school.email_1 }} </td>
                                                                                                                                              <td>{{ school.email_2 }} </td>
                                                                                                                                                                                                                                        </tr>
                        </tbody>
                  </table>
                </div>
              </section>
        </html-pdf>
        <!-- PDF Generator Ends -->

        <!-- Container Begins -->
        <div class="container-fluid" >
          <!-- Row begins-->
          <div class="row">
            <!-- Card Begins (Only Admin can see this content) -->
              <div class="card w-100" v-if="$gate.isAdmin()" > 
                <!-- card header -->
                <div class="card-header pr-sm-3">
                  <div class="d-flex mb-3">
                    <h3 class="card-title mr-auto ">School List </h3>
                    <button type="button" class="btn btn-sm btn-primary " @click="newModal">
                        <i class="fa fa-plus-square"></i>
                        Add New
                    </button>
                  </div>
                </div> <!-- /.card header -->

                <!-- card-body table container -->
                <div class="card-body table-responsive p-2"> 
                    <!-- VUE GOOD TABLE BEGINS -->  
                    <vue-good-table
                      mode="remote"
                      @on-page-change="onPageChange"
                      @on-sort-change="onSortChange"
                      @on-column-filter="onColumnFilter"
                      @on-per-page-change="onPerPageChange"
                      @on-search="onSearch"
                      @on-selected-rows-change="onSelectionChanged"

                      styleClass="vgt-table  bordered table-hover "
                      :totalRows="totalRecords"
                      :isLoading.sync="isLoading"
                      :select-options="{
                        enabled: true,
                      }"
                      :pagination-options="{
                        enabled: true,
                        perPageDropdown: [5, 10, 20, 50, 75, 100],
                        dropdownAllowAll: false,
                      }"
                      :search-options="{
                        enabled: true,
                        placeholder: 'Search the table',
                      }"
                      :rows="schools"
                      :columns="columns">
                          <!-- Vue Good TABLE CONTENTS and ACTIONS slot -->  
                          <div slot="table-actions">
                              <!-- Button Groups for EXPORTING TABLE -->  
                              <div class="mr-auto btn-group my-1" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                  <button id="btnGroupDrop1" type="button" class="btn btn-default btn-sm " data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-download"></i> Export
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button href="#" class="dropdown-item" @click.prevent="print">
                                        <i class="fa fa-print mr-1"></i> Print
                                    </button>  
                                      <button href="#" class="dropdown-item">
                                        <!-- JSON_EXCEL Component -->  
                                        <json-excel class="" :data="schools" :fields="table_heders" worksheet="School Lits" name="school_lists.xls">
                                            <i class="fa fa-file-excel mr-1"></i> Excel
                                        </json-excel>
                                    </button>
                                    <button href="#" class="dropdown-item" @click.prevent="generatePDF">
                                        <i class="fa fa-file-pdf mr-1"></i> PDF
                                    </button>    
                                  </div>
                                </div>
                              </div>

                              <!-- Button Groups for SHOWING/HIDING Columns -->  
                              <div class="mr-auto btn-group my-1" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                  <button id="btnGroupDrop1" type="button" class="btn btn-default btn-sm " data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-eye"></i> Show
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li v-for="(column, index) in columns" :key="index">
                                      <a href="#" class="dropdown-item" tabIndex="-1" @click.prevent="toggleColumn( index, $event )"><input :checked="!column.hidden" type="checkbox"/>&nbsp;&nbsp;{{column.label}}</a>
                                    </li>
                                  </div>
                                </div>
                              </div>
                          </div><!-- Vue Good Table Action slot and contents ends --> 
                          
                          <div slot="emptystate">
                            No {{$data['singular_lower']}} records found
                          </div>

                          <!-- Vue Good TABLE ACTION COLUMN options -->  
                          <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'action'">
                              <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary"  @click="schoolDetail(props)">Detail</button>
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>

                                <div class="dropdown-menu">
                                  <!--<a class="dropdown-item" href="#" @click="schoolDetail('show')"><i class="fa fa-eye"> <span style="margin-left:0.1em"> Details </span> </i></a>
                                  <div class="dropdown-divider"></div>-->
                                  <a class="dropdown-item" href="#" @click="editModal(props.row)"><i class="fa fa-edit">  <span style="margin-left:0.1em"> Edit </span>  </i></a>
                                  <a class="dropdown-item " href="#" @click="deleteSchool(props.row.id)"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
                                </div>
                              </div>
                            </span>
                            <span v-else>
                              {{props.formattedRow[props.column.field]}}
                            </span>
                          </template> <!-- Vue Good TABLE ACTION Column ends -->  

                          <!-- Vue Good TABLE SELECTED ROW Actions -->  
                          <div slot="selected-row-actions">
                              <div class="dropdown">
                                  <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Selected Schools
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item " href="#" @click="deleteSelectedSchools()"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
                                  </div>
                                </div>
                          </div>
                    </vue-good-table>
                </div> <!-- card-body table container ends -->

                <div class="card-footer">
                    <!--<pagination :data="schools" @pagination-change-page="getResults"></pagination>-->
                </div>
              </div> <!-- /.card ends-->
          </div> <!-- /.row ends-->
        </div> <!-- /.container ends-->

        <!-- Containt Not Found! -->
        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header"> <!-- Modal Header -->
                      <h5 class="modal-title" v-show="!editmode">New School</h5>
                      <h5 class="modal-title" v-show="editmode">Update School</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <!-- <form @submit.prevent="createModel"> -->
                  <form @submit.prevent="editmode ? updateSchool() : createSchool()">
                    <div class="modal-body">
                                                    <div class="form-group">
                                 
                                  <input type="hidden" v-model="form.id"></input>
                              
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Name</label>
                                  <input type="text" v-model="form.name" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has( 'name' ) }"  maxlength="255" >
                                                                        <has-error :form="form" field="name"></has-error>
                                                                                            </div>
                                                      <div class="form-group">
                                                                <label>Address</label>
                                  <textarea v-model="form.address"  :class="{ 'is-invalid': form.errors.has( 'address' ) }" class="form-control" style="min-height: 100px; max-height: 300px;"></textarea>
                                                                        <has-error :form="form" field="address"></has-error>
                                                                                            </div>
                                                      <div class="form-group">
                                                                <label>Phone no 1</label>
                                  <input type="text" v-model="form.phone_no_1" name="phone_no_1" class="form-control" :class="{ 'is-invalid': form.errors.has( 'phone_no_1' ) }"  maxlength="255" >
                                                                        <has-error :form="form" field="phone_no_1"></has-error>
                                                                                            </div>
                                                      <div class="form-group">
                                                                <label>Phone no 2</label>
                                  <input type="text" v-model="form.phone_no_2" name="phone_no_2" class="form-control" :class="{ 'is-invalid': form.errors.has( 'phone_no_2' ) }"  maxlength="255" >
                                                                        <has-error :form="form" field="phone_no_2"></has-error>
                                                                                            </div>
                                                      <div class="form-group">
                                                                <label>Email 1</label>
                                  <input type="text" v-model="form.email_1" name="email_1" class="form-control" :class="{ 'is-invalid': form.errors.has( 'email_1' ) }"  maxlength="255" >
                                                                        <has-error :form="form" field="email_1"></has-error>
                                                                                            </div>
                                                      <div class="form-group">
                                                                <label>Email 2</label>
                                  <input type="text" v-model="form.email_2" name="email_2" class="form-control" :class="{ 'is-invalid': form.errors.has( 'email_2' ) }"  maxlength="255" >
                                                                        <has-error :form="form" field="email_2"></has-error>
                                                                                            </div>
                                                      <div class="form-group">
                                 
                                  <input type="hidden" v-model="form.created_at"></input>
                              
                                                          </div>
                                                      <div class="form-group">
                                 
                                  <input type="hidden" v-model="form.updated_at"></input>
                              
                                                          </div>
                                              </div><!-- Modal body ends -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                  </form> <!-- Form Ends -->
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="schoolDetail" tabindex="-1" role="dialog" aria-labelledby="schoolDetail" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"> <!-- Modal Header -->
                        <h5 class="modal-title" > School Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-body">
                        <table class="table">
                          <tr class="row" v-for="(item, key, index) in clickedRow" v-if="!isSpecialColumn(key)">
                                <th class="text-primary col-4" style="text-align: right; margin-left:0em"> {{ ucAllWords(key) }} </th><td class="col-8"> {{ item }} </td>
                          </tr>
                        </table>
                    </div>    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" 
                        data-dismiss="modal" 
                        @click="deleteSchool(clickedRow.id)"><i class="fa fa-trash"></i> Delete </button>
                        <button type="button" class="btn btn-primary" 
                        data-dismiss="modal" 
                        @click="editModal(clickedRow)"><i class="fa fa-edit"></i> Edit</button>
                        <button type="button" class="btn btn-primary" 
                        data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                          
                    </div>
                </div>
            </div>
        </div>


  </section>
</template>


<script>
    import JsonExcel from "vue-json-excel";
    import VueHtml2pdf from "vue-html2pdf";

    export default {
        data () {
            return {
                editmode: false,
                schools : [],
                search : '',

                isLoading: false,
                totalRecords: 0,
                clickedRow: null,
                selectedRows: [],


                                                                                                                                                                                                                                                                                                                                                    
                serverParams: {
                  columnFilters: {
                  },
                  sort: [
                                                                                                                      {"type" : "asc",
                          "field" : "name"},
                                                                                                {"type" : "asc",
                          "field" : "address"},
                                                                                                {"type" : "asc",
                          "field" : "phone_no_1"},
                                                                                                {"type" : "asc",
                          "field" : "phone_no_2"},
                                                                                                {"type" : "asc",
                          "field" : "email_1"},
                                                                                                {"type" : "asc",
                          "field" : "email_2"},
                                                                                                                                                            ],
                  page: 1, 
                  perPage: 5,
                  searchTerm: '',
                },   
                     
                form: new Form({
                                        "id" : "",
                                        "name" : "",
                                        "address" : "",
                                        "phone_no_1" : "",
                                        "phone_no_2" : "",
                                        "email_1" : "",
                                        "email_2" : "",
                                        "created_at" : "",
                                        "updated_at" : "",
                                  }),
                
                table_heders: {
                                                                                                  "Name" : "name",
                                                                                "Address" : "address",
                                                                                "Phone No 1" : "phone_no_1",
                                                                                "Phone No 2" : "phone_no_2",
                                                                                "Email 1" : "email_1",
                                                                                "Email 2" : "email_2",
                                                                                                                                  },

                columns: [ 
                                        { label : "Id",
                      field : "id",
                                              hidden : true},
                                                              { label : "Name",
                      field : "name",
                                              hidden : false},
                                                              { label : "Address",
                      field : "address",
                                              hidden : false},
                                                              { label : "Phone No 1",
                      field : "phone_no_1",
                                              hidden : false},
                                                              { label : "Phone No 2",
                      field : "phone_no_2",
                                              hidden : false},
                                                              { label : "Email 1",
                      field : "email_1",
                                              hidden : false},
                                                              { label : "Email 2",
                      field : "email_2",
                                              hidden : false},
                                                              { label : "Created At",
                      field : "created_at",
                                              hidden : true},
                                                              { label : "Updated At",
                      field : "updated_at",
                                              hidden : true},
                                        
                  {
                    label: 'Actions',
                    field: 'action',
                    sortable: false,

                  },

                ],

            };
        },


        components: {
          "json-excel":JsonExcel,
          "html-pdf":VueHtml2pdf,  
        },

                               
        methods: {

            schoolDetail(params){
              this.clickedRow = params.row;
              $('#schoolDetail').modal('show');
            },

            updateSchool(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/schools/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');
                    this.loadSchools();
                })
                .catch(() => {
                    Toast.fire({
                          icon: 'error',
                          title: 'Some error occured!'
                      });
                    this.$Progress.fail();
                });
            },

            editModal(school){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(school);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            deleteSchool(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        // Send request to the server
                         if (result.value) {
                                const theData = [id];
                                this.form.delete('api/schools/'+JSON.stringify(theData) ).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The school was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadSchools();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },

            deleteSelectedSchools(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete "+this.selectedRows.length+" records? You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        // Send request to the server
                         if (result.value) {
                                let theData = JSON.stringify(this.selectedRows);
                                this.form.delete('api/schools/'+theData).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The school was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadSchools();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
            

            createSchool(){
                this.form.post('api/schools')
                .then((response)=>{
                    $('#addNew').modal('hide');
                    Toast.fire({
                          icon: 'success',
                          title: response.data.message
                    });
                    this.$Progress.finish();
                    this.loadSchools();
                })
                .catch(()=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured!'
                    });
                })
            },


            generatePDF() {
              this.$Progress.start();
              this.$refs.html2Pdf.generatePdf();
              this.$Progress.finish();
            },

            print () {
              this.$Progress.start();
              this.$htmlToPaper('printPaper');
              this.$Progress.finish();
            },
            

            updateParams(newProps) {
              this.serverParams = Object.assign({}, this.serverParams, newProps);
            },


            onSelectionChanged(params){
              this.selectedRows = [];
              for( var i=0; i< params.selectedRows.length; i++){
                if (params.selectedRows[i].vgtSelected)
                    this.selectedRows[i] = params.selectedRows[i].id;
              }
            },
            
            
            onPageChange(params) {
              this.updateParams({page: params.currentPage});
              this.loadSchools();
            },

            onPerPageChange(params) {
              this.updateParams({perPage: params.currentPerPage});
              this.loadSchools();
            },

            onSortChange(params) {
                var sortType = params[0].type;
                if(sortType != 'asc' && sortType != 'desc')
                  sortType = 'asc';
                this.updateParams({
                    sort: [{
                        type: sortType,
                        field: params[0].field,
                    }],
                });
                this.loadSchools();
            },


            onColumnFilter(params) {
              this.updateParams(params);
              this.loadSchools();
            },
            

            onSearch(params) {
              this.updateParams({searchTerm: params.searchTerm});
              this.loadSchools();
            },    


            toggleColumn( index, event ){
              // Set hidden to inverse of what it currently is
              this.$set( this.columns[ index ], 'hidden', ! this.columns[ index ].hidden );
            },


            // load items is what brings back the rows from server
            loadSchools() {
                this.$Progress.start();
                var parameters = "?perPage="+ this.serverParams.perPage;
                parameters = parameters + "&page="+ this.serverParams.page;
                parameters = parameters + "&sortField="+ this.serverParams.sort[0].field;
                parameters = parameters + "&sortType="+ this.serverParams.sort[0].type;
                parameters = parameters + "&searchTerm="+ this.serverParams.searchTerm;
                var url = "api/schools"+parameters;
                //console.log(JSON.stringify( url));
                try{
                    this.form.get( url ).then( schools  => {
                        if(schools.data.data){
                          this.totalRecords = schools.data.data.total
                          this.schools = schools.data.data.data;
                        }
                    });
                } catch(error){
                  console.log(error.message);
                };
                this.$Progress.finish();
            },


            ucAllWords(words) {
              var separateWord = words.toLowerCase().split('_');
              for (var i = 0; i < separateWord.length; i++) {
                  separateWord[i] = separateWord[i].charAt(0).toUpperCase() +
                  separateWord[i].substring(1);
              }
              return separateWord.join(' ');
            },


            isSpecialColumn(field){
              if(field != 'id' && field != 'updated_at' && field != 'created_at' 
                   && field != 'vgt_id' && field != 'vgtSelected' && field != 'originalIndex' ) 
                    return false;
                   else
                    return true;
            },

      
                                                                                                                                                                                                                                                                        



        },


        mounted() {
            //console.log('School Component mounted.')
            this.$Progress.start();
            this.loadSchools();
                                                                                                                                                                                                                                                                                    this.$Progress.finish();

        },


        created() {
            this.$Progress.start();
            this.loadSchools();
            this.$Progress.finish();
            
        },


        computed: {},

        
    }


</script>

<style>
@media  screen and (min-width: 990px) {
  #printPaper {
    margin-left: -22.2%;
    padding: 0% 1% 3% 1%;
    width: 100%;
  }
}
</style>



