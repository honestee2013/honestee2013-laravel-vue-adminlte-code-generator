<template>
  <section class="content">
        <!-- PDF Generator begins -->
        <html-pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="true"
            :paginate-elements-by-height="14000"
            filename="classroom_lists"
            :pdf-quality="2"
            :manual-pagination="true"
            pdf-format="a4"
            pdf-orientation="landscape"
            pdf-content-width="100%"
            ref="html2Pdf"
          >
              <section  id = "printPaper" slot="pdf-content" style=" width:100%; background-color: white;  padding: 0% 0.5% 40% 0.5%;">
                <div style = "margin-left: 0; width: 100%; ">
                  <h3 style="text-align:center; text-decoration: underline; padding: 1em; "> Classroom Lists</h3>
                  <table class="table table-bordered" style="width: 100%; ">
                        <thead>
                            <tr>
                                                                                                                                                              <th>Section Id</th>
                                                                                                                                <th>Name</th>
                                                                                                                                                                                                                      </tr>   
                        </thead>
                        <tbody >
                            <tr v-for="(classroom, index) in classrooms" :key="classroom.id">
                                                                                                                                                                              <td>{{ classroom.section_id }} </td>
                                                                                                                                              <td>{{ classroom.name }} </td>
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
                    <h3 class="card-title mr-auto ">Classroom List </h3>
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
                      :rows="classrooms"
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
                                        <json-excel class="" :data="classrooms" :fields="table_heders" worksheet="Classroom Lits" name="classroom_lists.xls">
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
                                <button type="button" class="btn btn-sm btn-primary"  @click="classroomDetail(props)">Detail</button>
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>

                                <div class="dropdown-menu">
                                  <!--<a class="dropdown-item" href="#" @click="classroomDetail('show')"><i class="fa fa-eye"> <span style="margin-left:0.1em"> Details </span> </i></a>
                                  <div class="dropdown-divider"></div>-->
                                  <a class="dropdown-item" href="#" @click="editModal(props.row)"><i class="fa fa-edit">  <span style="margin-left:0.1em"> Edit </span>  </i></a>
                                  <a class="dropdown-item " href="#" @click="deleteClassroom(props.row.id)"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
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
                                    Selected Classrooms
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item " href="#" @click="deleteSelectedClassrooms()"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
                                  </div>
                                </div>
                          </div>
                    </vue-good-table>
                </div> <!-- card-body table container ends -->

                <div class="card-footer">
                    <!--<pagination :data="classrooms" @pagination-change-page="getResults"></pagination>-->
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
                      <h5 class="modal-title" v-show="!editmode">New Classroom</h5>
                      <h5 class="modal-title" v-show="editmode">Update Classroom</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <!-- <form @submit.prevent="createModel"> -->
                  <form @submit.prevent="editmode ? updateClassroom() : createClassroom()">
                    <div class="modal-body">
                                                    <div class="form-group">
                                 
                                  <input type="hidden" v-model="form.id"></input>
                              
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Section</label>
                                  <select v-model="form.section_id" name="section_id" class="form-control" 
                                          :class="{ 'is-invalid': form.errors.has( 'section_id' ) }">
                                      <option v-for="(item, index)  in sections"
                                            :key= "index" :value="item.id"> {{item.name}} </option>
                                  </select>
                                                                        <has-error :form="form" field="section_id"></has-error>
                                  
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Name</label>
                                  <input type="text" v-model="form.name" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has( 'name' ) }"  maxlength="255" >
                                                                        <has-error :form="form" field="name"></has-error>
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
        <div class="modal fade" id="classroomDetail" tabindex="-1" role="dialog" aria-labelledby="classroomDetail" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"> <!-- Modal Header -->
                        <h5 class="modal-title" > Classroom Detail</h5>
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
                        @click="deleteClassroom(clickedRow.id)"><i class="fa fa-trash"></i> Delete </button>
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
                classrooms : [],
                search : '',

                isLoading: false,
                totalRecords: 0,
                clickedRow: null,
                selectedRows: [],


                                                                                                sections: [],          
                                                                                                                                                
                serverParams: {
                  columnFilters: {
                  },
                  sort: [
                                                                                                                      {"type" : "asc",
                          "field" : "section_id"},
                                                                                                {"type" : "asc",
                          "field" : "name"},
                                                                                                                                                            ],
                  page: 1, 
                  perPage: 5,
                  searchTerm: '',
                },   
                     
                form: new Form({
                                        "id" : "",
                                        "section_id" : "",
                                        "name" : "",
                                        "created_at" : "",
                                        "updated_at" : "",
                                  }),
                
                table_heders: {
                                                                                                  "Section Id" : "section_id",
                                                                                "Name" : "name",
                                                                                                                                  },

                columns: [ 
                                        { label : "Id",
                      field : "id",
                                              hidden : true},
                                                              { label : "Section Id",
                      field : "section_id",
                                              hidden : false},
                                                              { label : "Name",
                      field : "name",
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

            classroomDetail(params){
              this.clickedRow = params.row;
              $('#classroomDetail').modal('show');
            },

            updateClassroom(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/classrooms/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');
                    this.loadClassrooms();
                })
                .catch(() => {
                    Toast.fire({
                          icon: 'error',
                          title: 'Some error occured!'
                      });
                    this.$Progress.fail();
                });
            },

            editModal(classroom){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(classroom);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            deleteClassroom(id){
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
                                this.form.delete('api/classrooms/'+JSON.stringify(theData) ).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The classroom was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadClassrooms();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },

            deleteSelectedClassrooms(){
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
                                this.form.delete('api/classrooms/'+theData).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The classroom was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadClassrooms();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
            

            createClassroom(){
                this.form.post('api/classrooms')
                .then((response)=>{
                    $('#addNew').modal('hide');
                    Toast.fire({
                          icon: 'success',
                          title: response.data.message
                    });
                    this.$Progress.finish();
                    this.loadClassrooms();
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
              this.loadClassrooms();
            },

            onPerPageChange(params) {
              this.updateParams({perPage: params.currentPerPage});
              this.loadClassrooms();
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
                this.loadClassrooms();
            },


            onColumnFilter(params) {
              this.updateParams(params);
              this.loadClassrooms();
            },
            

            onSearch(params) {
              this.updateParams({searchTerm: params.searchTerm});
              this.loadClassrooms();
            },    


            toggleColumn( index, event ){
              // Set hidden to inverse of what it currently is
              this.$set( this.columns[ index ], 'hidden', ! this.columns[ index ].hidden );
            },


            // load items is what brings back the rows from server
            loadClassrooms() {
                this.$Progress.start();
                var parameters = "?perPage="+ this.serverParams.perPage;
                parameters = parameters + "&page="+ this.serverParams.page;
                parameters = parameters + "&sortField="+ this.serverParams.sort[0].field;
                parameters = parameters + "&sortType="+ this.serverParams.sort[0].type;
                parameters = parameters + "&searchTerm="+ this.serverParams.searchTerm;
                var url = "api/classrooms"+parameters;
                //console.log(JSON.stringify( url));
                try{
                    this.form.get( url ).then( classrooms  => {
                        if(classrooms.data.data){
                          this.totalRecords = classrooms.data.data.total
                          this.classrooms = classrooms.data.data.data;
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

      
                                                                            loadSections(){


                          try{
                              var url = "api/sections";
                              axios.get( url ).then( sections  => {
                                  if(sections.data.data.data){
                                    this.sections = sections.data.data.data;
                                  }
                              });
                          } catch(error){
                            console.log(error.message);
                          };

                    },
                                                                                                                



        },


        mounted() {
            //console.log('Classroom Component mounted.')
            this.$Progress.start();
            this.loadClassrooms();
                                                                            this.loadSections();          
                                                                                                                            this.$Progress.finish();

        },


        created() {
            this.$Progress.start();
            this.loadClassrooms();
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



