<template>
  <section class="content">
        <!-- PDF Generator begins -->
        <html-pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="true"
            :paginate-elements-by-height="14000"
            filename="subject_lists"
            :pdf-quality="2"
            :manual-pagination="true"
            pdf-format="a4"
            pdf-orientation="landscape"
            pdf-content-width="100%"
            ref="html2Pdf"
          >
              <section  id = "printPaper" slot="pdf-content" style=" width:100%; background-color: white;  padding: 0% 0.5% 40% 0.5%;">
                <div style = "margin-left: 0; width: 100%; ">
                  <h3 style="text-align:center; text-decoration: underline; padding: 1em; "> Subject Lists</h3>
                  <table class="table table-bordered" style="width: 100%; ">
                        <thead>
                            <tr>
                                                                                                                                                              <th>Name</th>
                                                                                                                                                                                                                      </tr>   
                        </thead>
                        <tbody >
                            <tr v-for="(subject, index) in subjects" :key="subject.id">
                                                                                                                                                                              <td>{{ subject.name }} </td>
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
                    <h3 class="card-title mr-auto ">Subject List </h3>
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
                      :rows="subjects"
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
                                        <json-excel class="" :data="subjects" :fields="table_heders" worksheet="Subject Lits" name="subject_lists.xls">
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
                                <button type="button" class="btn btn-sm btn-primary"  @click="subjectDetail(props)">Detail</button>
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>

                                <div class="dropdown-menu">
                                  <!--<a class="dropdown-item" href="#" @click="subjectDetail('show')"><i class="fa fa-eye"> <span style="margin-left:0.1em"> Details </span> </i></a>
                                  <div class="dropdown-divider"></div>-->
                                  <a class="dropdown-item" href="#" @click="editModal(props.row)"><i class="fa fa-edit">  <span style="margin-left:0.1em"> Edit </span>  </i></a>
                                  <a class="dropdown-item " href="#" @click="deleteSubject(props.row.id)"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
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
                                    Selected Subjects
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item " href="#" @click="deleteSelectedSubjects()"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
                                  </div>
                                </div>
                          </div>
                    </vue-good-table>
                </div> <!-- card-body table container ends -->

                <div class="card-footer">
                    <!--<pagination :data="subjects" @pagination-change-page="getResults"></pagination>-->
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
                      <h5 class="modal-title" v-show="!editmode">New Subject</h5>
                      <h5 class="modal-title" v-show="editmode">Update Subject</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <!-- <form @submit.prevent="createModel"> -->
                  <form @submit.prevent="editmode ? updateSubject() : createSubject()">
                    <div class="modal-body">
                                                    <div class="form-group">
                                 
                                  <input type="hidden" v-model="form.id"></input>
                              
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Name</label>
                                  <select v-model="form.name" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has( 'name' ) }">
                                                                                <option> Number work </option>
                                                                                <option> Letter work </option>
                                                                                <option> Phonics </option>
                                                                                <option> Social habit </option>
                                                                                <option> Health habit </option>
                                                                                <option> Scientific and Reflective thinking </option>
                                                                                <option> Writing or Patterns </option>
                                                                                <option> Bible or Islamic knowledge </option>
                                                                                <option> Creative Arts </option>
                                                                                <option> Rhymes or Songs </option>
                                                                                <option> Computer </option>
                                                                                <option> Physical exercise </option>
                                                                                <option> Story telling </option>
                                                                                <option> English Language </option>
                                                                                <option> English Primary 1 </option>
                                                                                <option> English Primary 2 </option>
                                                                                <option> English Primary 3 </option>
                                                                                <option> English Primary 4 </option>
                                                                                <option> English Primary 5 </option>
                                                                                <option> English Primary 6 </option>
                                                                                <option> Mathematics </option>
                                                                                <option> Math Primary 1 </option>
                                                                                <option> Math Primary 2 </option>
                                                                                <option> Math Primary 3 </option>
                                                                                <option> Math Primary 4 </option>
                                                                                <option> Math Primary 5 </option>
                                                                                <option> Math Primary 6 </option>
                                                                                <option> Verbal Reasoning </option>
                                                                                <option> Spelling </option>
                                                                                <option> Quantitative Reasoning </option>
                                                                                <option> Elementary Science </option>
                                                                                <option> Basic science primary 1 </option>
                                                                                <option> Social Studies </option>
                                                                                <option> Vocational Aptitude </option>
                                                                                <option> Health Education </option>
                                                                                <option> Bible Knowledge </option>
                                                                                <option> Languages </option>
                                                                                <option> Agric Science </option>
                                                                                <option> Home Economics </option>
                                                                                <option> Civic </option>
                                                                                <option> Music </option>
                                                                                <option> Moral Instruction </option>
                                                                                <option> Handwriting </option>
                                                                                <option> Yoruba or Hausa or Ibo </option>
                                                                                <option> Basic Science </option>
                                                                                <option> Fine Arts or Creative Art </option>
                                                                                <option> Agricultural Science </option>
                                                                                <option> Civic Education </option>
                                                                                <option> CRS or IRK </option>
                                                                                <option> Physical and Health Education </option>
                                                                                <option> Business Studies </option>
                                                                                <option> French </option>
                                                                                <option> Computer Studies </option>
                                                                                <option> Basic Technology </option>
                                                                                <option> Arabic </option>
                                                                                <option> Auto Parts Merchandising </option>
                                                                                <option> Auto Mechanics </option>
                                                                                <option> Auto Mechanical Work </option>
                                                                                <option> Auto Electrical Work </option>
                                                                                <option> Auto Body Repairs and Spray Painting </option>
                                                                                <option> Applied Electricity or Basic Electricity </option>
                                                                                <option> Animal Husbandry </option>
                                                                                <option> Business Management </option>
                                                                                <option> Building Construction </option>
                                                                                <option> Book Keeping </option>
                                                                                <option> Blocklaying </option>
                                                                                <option>  Bricklaying and Concreting </option>
                                                                                <option> Biology </option>
                                                                                <option> Basketry </option>
                                                                                <option> Crop Husbandry and Horticulture </option>
                                                                                <option> Cosmetology </option>
                                                                                <option> Commerce </option>
                                                                                <option> Clothing and Textiles </option>
                                                                                <option> Clerical Office Duties </option>
                                                                                <option> Christian Religious Studies </option>
                                                                                <option> Chemistry </option>
                                                                                <option> Ceramics </option>
                                                                                <option> Catering Craft Practice </option>
                                                                                <option> Capentry and Joinery </option>
                                                                                <option> Dyeing &amp; Bleaching </option>
                                                                                <option> Data Processing </option>
                                                                                <option> Electronics or Basic Electronics </option>
                                                                                <option> Electrical Installation and Maintenance </option>
                                                                                <option> Efik </option>
                                                                                <option> Edo </option>
                                                                                <option> Economics </option>
                                                                                <option> Further Mathematics </option>
                                                                                <option> Furniture Making </option>
                                                                                <option> Forestry </option>
                                                                                <option> Foods and Nutrition </option>
                                                                                <option> Fisheries </option>
                                                                                <option> Financial Accounting </option>
                                                                                <option> Typewriting </option>
                                                                                <option> Tourism </option>
                                                                                <option> Textiles </option>
                                                                                <option> Technical Drawing </option>
                                                                                <option> Store Management </option>
                                                                                <option> Store Keeping </option>
                                                                                <option> Shorthand </option>
                                                                                <option> Sculpture </option>
                                                                                <option> Salesmanship </option>
                                                                                <option> Refrigeration and Air Conditioning </option>
                                                                                <option> Radio </option>
                                                                                <option> television and Electronics Works </option>
                                                                                <option> Printing Craft Practice </option>
                                                                                <option> Principles of Cost Accounting </option>
                                                                                <option> Plumbing and Pipe Fitting </option>
                                                                                <option> Picture Making </option>
                                                                                <option> Physics </option>
                                                                                <option> Physical Education </option>
                                                                                <option> Photography </option>
                                                                                <option> Painting and Decorating </option>
                                                                                <option> Office Practice </option>
                                                                                <option> Mining </option>
                                                                                <option> Metalwork </option>
                                                                                <option> Marketing </option>
                                                                                <option> Machine Woodworking </option>
                                                                                <option> Literature in English </option>
                                                                                <option> Leatherwork </option>
                                                                                <option> Leather Goods </option>
                                                                                <option> Jewellery </option>
                                                                                <option> Islamic Religious Studies </option>
                                                                                <option> Integrated Science </option>
                                                                                <option> Insurance </option>
                                                                                <option> Information and Communication Technology </option>
                                                                                <option> Igbo </option>
                                                                                <option> Ibibio </option>
                                                                                <option> Home Management </option>
                                                                                <option> History </option>
                                                                                <option> Health Education or Health Science </option>
                                                                                <option> Hausa </option>
                                                                                <option> Gsm Phones Maintenance and Repairs </option>
                                                                                <option> Graphic Design </option>
                                                                                <option> Government </option>
                                                                                <option> Ghanaian Languages </option>
                                                                                <option> Geography </option>
                                                                                <option> General Mathematics or Mathematics </option>
                                                                                <option> General Knowledge in Art </option>
                                                                                <option> General Agriculture </option>
                                                                                <option> Garment Making </option>
                                                                                <option> Visual Art </option>
                                                                                <option> Upholstery </option>
                                                                                <option> Woodwork </option>
                                                                                <option> West African Traditional Religion </option>
                                                                                <option> Welding and Fabrication Engineering Craft Practice </option>
                                                                                <option> Yoruba </option>
                                                                        </select>
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
        <div class="modal fade" id="subjectDetail" tabindex="-1" role="dialog" aria-labelledby="subjectDetail" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"> <!-- Modal Header -->
                        <h5 class="modal-title" > Subject Detail</h5>
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
                        @click="deleteSubject(clickedRow.id)"><i class="fa fa-trash"></i> Delete </button>
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
                subjects : [],
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
                                                                                                                                                            ],
                  page: 1, 
                  perPage: 5,
                  searchTerm: '',
                },   
                     
                form: new Form({
                                        "id" : "",
                                        "name" : "",
                                        "created_at" : "",
                                        "updated_at" : "",
                                  }),
                
                table_heders: {
                                                                                                  "Name" : "name",
                                                                                                                                  },

                columns: [ 
                                        { label : "Id",
                      field : "id",
                                              hidden : true},
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

            subjectDetail(params){
              this.clickedRow = params.row;
              $('#subjectDetail').modal('show');
            },

            updateSubject(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/subjects/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');
                    this.loadSubjects();
                })
                .catch(() => {
                    Toast.fire({
                          icon: 'error',
                          title: 'Some error occured!'
                      });
                    this.$Progress.fail();
                });
            },

            editModal(subject){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(subject);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            deleteSubject(id){
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
                                this.form.delete('api/subjects/'+JSON.stringify(theData) ).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The subject was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadSubjects();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },

            deleteSelectedSubjects(){
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
                                this.form.delete('api/subjects/'+theData).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The subject was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadSubjects();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
            

            createSubject(){
                this.form.post('api/subjects')
                .then((response)=>{
                    $('#addNew').modal('hide');
                    Toast.fire({
                          icon: 'success',
                          title: response.data.message
                    });
                    this.$Progress.finish();
                    this.loadSubjects();
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
              this.loadSubjects();
            },

            onPerPageChange(params) {
              this.updateParams({perPage: params.currentPerPage});
              this.loadSubjects();
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
                this.loadSubjects();
            },


            onColumnFilter(params) {
              this.updateParams(params);
              this.loadSubjects();
            },
            

            onSearch(params) {
              this.updateParams({searchTerm: params.searchTerm});
              this.loadSubjects();
            },    


            toggleColumn( index, event ){
              // Set hidden to inverse of what it currently is
              this.$set( this.columns[ index ], 'hidden', ! this.columns[ index ].hidden );
            },


            // load items is what brings back the rows from server
            loadSubjects() {
                this.$Progress.start();
                var parameters = "?perPage="+ this.serverParams.perPage;
                parameters = parameters + "&page="+ this.serverParams.page;
                parameters = parameters + "&sortField="+ this.serverParams.sort[0].field;
                parameters = parameters + "&sortType="+ this.serverParams.sort[0].type;
                parameters = parameters + "&searchTerm="+ this.serverParams.searchTerm;
                var url = "api/subjects"+parameters;
                //console.log(JSON.stringify( url));
                try{
                    this.form.get( url ).then( subjects  => {
                        if(subjects.data.data){
                          this.totalRecords = subjects.data.data.total
                          this.subjects = subjects.data.data.data;
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
            }

        },


        mounted() {
            //console.log('Subject Component mounted.')
            this.$Progress.start();
            this.loadSubjects();
            this.$Progress.finish();

        },


        created() {
            this.$Progress.start();
            this.loadSubjects();
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



