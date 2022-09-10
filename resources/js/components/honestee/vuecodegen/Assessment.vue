<template>
  <section class="content">
        <!-- PDF Generator begins -->
        <html-pdf
            :show-layout="false"
            :float-layout="true"
            :enable-download="true"
            :preview-modal="true"
            :paginate-elements-by-height="14000"
            filename="assessment_lists"
            :pdf-quality="2"
            :manual-pagination="true"
            pdf-format="a4"
            pdf-orientation="landscape"
            pdf-content-width="100%"
            ref="html2Pdf"
          >
              <section  id = "printPaper" slot="pdf-content" style=" width:100%; background-color: white;  padding: 0% 0.5% 40% 0.5%;">
                <div style = "margin-left: 0; width: 100%; ">
                  <h3 style="text-align:center; text-decoration: underline; padding: 1em; "> Assessment Lists</h3>
                  <table class="table table-bordered" style="width: 100%; ">
                        <thead>
                            <tr>
                                                                                                                                                              <th>User Id</th>
                                                                                                                                <th>Subject Id</th>
                                                                                                                                <th>Name</th>
                                                                                                                                <th>Year</th>
                                                                                                                                <th>Term</th>
                                                                                                                                <th>Sequence</th>
                                                                                                                                <th>Score</th>
                                                                                                                                                                                                                      </tr>   
                        </thead>
                        <tbody >
                            <tr v-for="(assessment, index) in assessments" :key="assessment.id">
                                                                                                                                                                              <td>{{ assessment.user_id }} </td>
                                                                                                                                              <td>{{ assessment.subject_id }} </td>
                                                                                                                                              <td>{{ assessment.name }} </td>
                                                                                                                                              <td>{{ assessment.year }} </td>
                                                                                                                                              <td>{{ assessment.term }} </td>
                                                                                                                                              <td>{{ assessment.sequence }} </td>
                                                                                                                                              <td>{{ assessment.score }} </td>
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
                    <h3 class="card-title mr-auto ">Assessment List </h3>
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
                      :rows="assessments"
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
                                        <json-excel class="" :data="assessments" :fields="table_heders" worksheet="Assessment Lits" name="assessment_lists.xls">
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
                                <button type="button" class="btn btn-sm btn-primary"  @click="assessmentDetail(props)">Detail</button>
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>

                                <div class="dropdown-menu">
                                  <!--<a class="dropdown-item" href="#" @click="assessmentDetail('show')"><i class="fa fa-eye"> <span style="margin-left:0.1em"> Details </span> </i></a>
                                  <div class="dropdown-divider"></div>-->
                                  <a class="dropdown-item" href="#" @click="editModal(props.row)"><i class="fa fa-edit">  <span style="margin-left:0.1em"> Edit </span>  </i></a>
                                  <a class="dropdown-item " href="#" @click="deleteAssessment(props.row.id)"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
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
                                    Selected Assessments
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item " href="#" @click="deleteSelectedAssessments()"><i class="fa fa-trash">  <span style="margin-left:0.1em"> Delete </span>  </i></a>
                                  </div>
                                </div>
                          </div>
                    </vue-good-table>
                </div> <!-- card-body table container ends -->

                <div class="card-footer">
                    <!--<pagination :data="assessments" @pagination-change-page="getResults"></pagination>-->
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
                      <h5 class="modal-title" v-show="!editmode">New Assessment</h5>
                      <h5 class="modal-title" v-show="editmode">Update Assessment</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <!-- <form @submit.prevent="createModel"> -->
                  <form @submit.prevent="editmode ? updateAssessment() : createAssessment()">
                    <div class="modal-body">
                                                    <div class="form-group">
                                 
                                  <input type="hidden" v-model="form.id"></input>
                              
                                                          </div>
                                                      <div class="form-group">
                                                                <label>User</label>
                                  <select v-model="form.user_id" name="user_id" class="form-control" :class="{ 'is-invalid': form.errors.has( 'user_id' ) }">
                                                                                <option value="1"> John Doe </option>
                                                                        </select>
                                                                        <has-error :form="form" field="user_id"></has-error>
                                  
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Subject</label>
                                  <select v-model="form.subject_id" name="subject_id" class="form-control" :class="{ 'is-invalid': form.errors.has( 'subject_id' ) }">
                                                                                <option value="1"> Bible or Islamic knowledge </option>
                                                                                <option value="2"> Creative Arts </option>
                                                                                <option value="3"> Rhymes or Songs </option>
                                                                                <option value="4"> Computer </option>
                                                                                <option value="5"> Physical exercise </option>
                                                                                <option value="6"> Story telling </option>
                                                                                <option value="7"> English Language </option>
                                                                                <option value="8"> English Primary 1 </option>
                                                                                <option value="9"> English Primary 2 </option>
                                                                                <option value="10"> English Primary 3 </option>
                                                                                <option value="11"> English Primary 4 </option>
                                                                                <option value="12"> English Primary 5 </option>
                                                                                <option value="13"> English Primary 6 </option>
                                                                                <option value="14"> Mathematics </option>
                                                                                <option value="15"> Math Primary 1 </option>
                                                                                <option value="16"> Math Primary 2 </option>
                                                                                <option value="17"> Math Primary 3 </option>
                                                                                <option value="18"> Math Primary 4 </option>
                                                                                <option value="19"> Math Primary 5 </option>
                                                                                <option value="20"> Math Primary 6 </option>
                                                                                <option value="21"> Verbal Reasoning </option>
                                                                                <option value="22"> Spelling </option>
                                                                                <option value="23"> Quantitative Reasoning </option>
                                                                                <option value="24"> Elementary Science </option>
                                                                                <option value="25"> Basic science primary 1 </option>
                                                                                <option value="26"> Social Studies </option>
                                                                                <option value="27"> Vocational Aptitude </option>
                                                                                <option value="28"> Health Education </option>
                                                                                <option value="29"> Bible Knowledge </option>
                                                                                <option value="30"> Languages </option>
                                                                                <option value="31"> Agric Science </option>
                                                                                <option value="32"> Home Economics </option>
                                                                                <option value="33"> Civic </option>
                                                                                <option value="34"> Music </option>
                                                                                <option value="35"> Moral Instruction </option>
                                                                                <option value="36"> Handwriting </option>
                                                                                <option value="37"> Yoruba or Hausa or Ibo </option>
                                                                                <option value="38"> Basic Science </option>
                                                                                <option value="39"> Fine Arts or Creative Art </option>
                                                                                <option value="40"> Agricultural Science </option>
                                                                                <option value="41"> Civic Education </option>
                                                                                <option value="42"> Physical and Health Education </option>
                                                                                <option value="43"> Business Studies </option>
                                                                                <option value="44"> French </option>
                                                                                <option value="45"> Computer Studies </option>
                                                                                <option value="46"> Basic Technology </option>
                                                                                <option value="47"> Arabic </option>
                                                                                <option value="48"> Auto Parts Merchandising </option>
                                                                                <option value="49"> Auto Mechanics </option>
                                                                                <option value="50"> Auto Mechanical Work </option>
                                                                                <option value="51"> Auto Electrical Work </option>
                                                                                <option value="52"> Auto Body Repairs and Spray Painting </option>
                                                                                <option value="53"> Applied Electricity or Basic Electricity </option>
                                                                                <option value="54"> Animal Husbandry </option>
                                                                                <option value="55"> Business Management </option>
                                                                                <option value="56"> Building Construction </option>
                                                                                <option value="57"> Book Keeping </option>
                                                                                <option value="58"> Blocklaying, Bricklaying and Concreting </option>
                                                                                <option value="59"> Biology </option>
                                                                                <option value="60"> Basketry </option>
                                                                                <option value="61"> Crop Husbandry and Horticulture </option>
                                                                                <option value="62"> Cosmetology </option>
                                                                                <option value="63"> Commerce </option>
                                                                                <option value="64"> Clothing and Textiles </option>
                                                                                <option value="65"> Clerical Office Duties </option>
                                                                                <option value="66"> Christian Religious Studies </option>
                                                                                <option value="67"> Chemistry </option>
                                                                                <option value="68"> Ceramics </option>
                                                                                <option value="69"> Catering Craft Practice </option>
                                                                                <option value="70"> Capentry and Joinery </option>
                                                                                <option value="71"> Dyeing &amp; Bleaching </option>
                                                                                <option value="72"> Data Processing </option>
                                                                                <option value="73"> Electronics or Basic Electronics </option>
                                                                                <option value="74"> Electrical Installation and Maintenance </option>
                                                                                <option value="75"> Efik </option>
                                                                                <option value="76"> Edo </option>
                                                                                <option value="77"> Economics </option>
                                                                                <option value="78"> Further Mathematics </option>
                                                                                <option value="79"> Furniture Making </option>
                                                                                <option value="80"> Forestry </option>
                                                                                <option value="81"> Foods and Nutrition </option>
                                                                                <option value="82"> Fisheries </option>
                                                                                <option value="83"> Financial Accounting </option>
                                                                                <option value="84"> Typewriting </option>
                                                                                <option value="85"> Tourism </option>
                                                                                <option value="86"> Textiles </option>
                                                                                <option value="87"> Technical Drawing </option>
                                                                                <option value="88"> Store Management </option>
                                                                                <option value="89"> Store Keeping </option>
                                                                                <option value="90"> Shorthand </option>
                                                                                <option value="91"> Sculpture </option>
                                                                                <option value="92"> Salesmanship </option>
                                                                                <option value="93"> Refrigeration and Air Conditioning </option>
                                                                                <option value="94"> Radio,television and Electronics Works </option>
                                                                                <option value="95"> Printing Craft Practice </option>
                                                                                <option value="96"> Principles of Cost Accounting </option>
                                                                                <option value="97"> Plumbing and Pipe Fitting </option>
                                                                                <option value="98"> Picture Making </option>
                                                                                <option value="99"> Physics </option>
                                                                                <option value="100"> Physical Education </option>
                                                                                <option value="101"> Photography </option>
                                                                                <option value="102"> Painting and Decorating </option>
                                                                                <option value="103"> Office Practice </option>
                                                                                <option value="104"> Mining </option>
                                                                                <option value="105"> Metalwork </option>
                                                                                <option value="106"> Marketing </option>
                                                                                <option value="107"> Machine Woodworking </option>
                                                                                <option value="108"> Literature in English </option>
                                                                                <option value="109"> Leatherwork </option>
                                                                                <option value="110"> Leather Goods </option>
                                                                                <option value="111"> Jewellery </option>
                                                                                <option value="112"> Islamic Religious Studies </option>
                                                                                <option value="113"> Integrated Science </option>
                                                                                <option value="114"> Insurance </option>
                                                                                <option value="115"> Information and Communication Technology </option>
                                                                                <option value="116"> Igbo </option>
                                                                                <option value="117"> Ibibio </option>
                                                                                <option value="118"> Home Management </option>
                                                                                <option value="119"> History </option>
                                                                                <option value="120"> Health Education or Health Science </option>
                                                                                <option value="121"> Hausa </option>
                                                                                <option value="122"> Gsm Phones Maintenance and Repairs </option>
                                                                                <option value="123"> Graphic Design </option>
                                                                                <option value="124"> Government </option>
                                                                                <option value="125"> Ghanaian Languages </option>
                                                                                <option value="126"> Geography </option>
                                                                                <option value="127"> General Mathematics or Mathematics </option>
                                                                                <option value="128"> General Knowledge in Art </option>
                                                                                <option value="129"> General Agriculture </option>
                                                                                <option value="130"> Garment Making </option>
                                                                                <option value="131"> Visual Art </option>
                                                                                <option value="132"> Upholstery </option>
                                                                                <option value="133"> Woodwork </option>
                                                                                <option value="134"> West African Traditional Religion </option>
                                                                                <option value="135"> Welding and Fabrication Engineering Craft Practice </option>
                                                                                <option value="136"> Yoruba </option>
                                                                        </select>
                                                                        <has-error :form="form" field="subject_id"></has-error>
                                  
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Name</label>
                                  <select v-model="form.name" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has( 'name' ) }">
                                                                                <option> CA </option>
                                                                                <option> Test </option>
                                                                                <option> Exams </option>
                                                                        </select>
                                                                        <has-error :form="form" field="name"></has-error>
                                                                                          
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Year</label>
                                  <select v-model="form.year" name="year" class="form-control" :class="{ 'is-invalid': form.errors.has( 'year' ) }">
                                                                                <option> 2022 </option>
                                                                                <option> 2023 </option>
                                                                                <option> 2024 </option>
                                                                                <option> 2025 </option>
                                                                                <option> 2026 </option>
                                                                                <option> 2027 </option>
                                                                                <option> 2028 </option>
                                                                                <option> 2029 </option>
                                                                                <option> 2030 </option>
                                                                        </select>
                                                                        <has-error :form="form" field="year"></has-error>
                                                                                          
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Term</label>
                                  <select v-model="form.term" name="term" class="form-control" :class="{ 'is-invalid': form.errors.has( 'term' ) }">
                                                                                <option> First </option>
                                                                                <option> Second </option>
                                                                                <option> Third </option>
                                                                        </select>
                                                                        <has-error :form="form" field="term"></has-error>
                                                                                          
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Sequence</label>
                                  <select v-model="form.sequence" name="sequence" class="form-control" :class="{ 'is-invalid': form.errors.has( 'sequence' ) }">
                                                                                <option> First </option>
                                                                                <option> Second </option>
                                                                                <option> Third </option>
                                                                        </select>
                                                                        <has-error :form="form" field="sequence"></has-error>
                                                                                          
                                                          </div>
                                                      <div class="form-group">
                                                                <label>Score</label>
                                  <input type="number" v-model="form.score" class="form-control" :class="{ 'is-invalid': form.errors.has( 'score' ) }"></input>
                                                                        <has-error :form="form" field="score"></has-error>
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
        <div class="modal fade" id="assessmentDetail" tabindex="-1" role="dialog" aria-labelledby="assessmentDetail" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"> <!-- Modal Header -->
                        <h5 class="modal-title" > Assessment Detail</h5>
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
                        @click="deleteAssessment(clickedRow.id)"><i class="fa fa-trash"></i> Delete </button>
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
                assessments : [],
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
                          "field" : "user_id"},
                                                                                                {"type" : "asc",
                          "field" : "subject_id"},
                                                                                                {"type" : "asc",
                          "field" : "name"},
                                                                                                {"type" : "asc",
                          "field" : "year"},
                                                                                                {"type" : "asc",
                          "field" : "term"},
                                                                                                {"type" : "asc",
                          "field" : "sequence"},
                                                                                                {"type" : "asc",
                          "field" : "score"},
                                                                                                                                                            ],
                  page: 1, 
                  perPage: 5,
                  searchTerm: '',
                },   
                     
                form: new Form({
                                        "id" : "",
                                        "user_id" : "",
                                        "subject_id" : "",
                                        "name" : "",
                                        "year" : "",
                                        "term" : "",
                                        "sequence" : "",
                                        "score" : "",
                                        "created_at" : "",
                                        "updated_at" : "",
                                  }),
                
                table_heders: {
                                                                                                  "User Id" : "user_id",
                                                                                "Subject Id" : "subject_id",
                                                                                "Name" : "name",
                                                                                "Year" : "year",
                                                                                "Term" : "term",
                                                                                "Sequence" : "sequence",
                                                                                "Score" : "score",
                                                                                                                                  },

                columns: [ 
                                        { label : "Id",
                      field : "id",
                                              hidden : true},
                                                              { label : "User Id",
                      field : "user_id",
                                              hidden : false},
                                                              { label : "Subject Id",
                      field : "subject_id",
                                              hidden : false},
                                                              { label : "Name",
                      field : "name",
                                              hidden : false},
                                                              { label : "Year",
                      field : "year",
                                              hidden : false},
                                                              { label : "Term",
                      field : "term",
                                              hidden : false},
                                                              { label : "Sequence",
                      field : "sequence",
                                              hidden : false},
                                                              { label : "Score",
                      field : "score",
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

            assessmentDetail(params){
              this.clickedRow = params.row;
              $('#assessmentDetail').modal('show');
            },

            updateAssessment(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/assessments/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');
                    this.loadAssessments();
                })
                .catch(() => {
                    Toast.fire({
                          icon: 'error',
                          title: 'Some error occured!'
                      });
                    this.$Progress.fail();
                });
            },

            editModal(assessment){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(assessment);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            deleteAssessment(id){
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
                                this.form.delete('api/assessments/'+JSON.stringify(theData) ).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The assessment was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadAssessments();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },

            deleteSelectedAssessments(){
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
                                this.form.delete('api/assessments/'+theData).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'The assessment was deleted successfully.',
                                        'success'
                                        );
                                    // Fire.$emit('AfterCreate');
                                    this.loadAssessments();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
            

            createAssessment(){
                this.form.post('api/assessments')
                .then((response)=>{
                    $('#addNew').modal('hide');
                    Toast.fire({
                          icon: 'success',
                          title: response.data.message
                    });
                    this.$Progress.finish();
                    this.loadAssessments();
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
              this.loadAssessments();
            },

            onPerPageChange(params) {
              this.updateParams({perPage: params.currentPerPage});
              this.loadAssessments();
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
                this.loadAssessments();
            },


            onColumnFilter(params) {
              this.updateParams(params);
              this.loadAssessments();
            },
            

            onSearch(params) {
              this.updateParams({searchTerm: params.searchTerm});
              this.loadAssessments();
            },    


            toggleColumn( index, event ){
              // Set hidden to inverse of what it currently is
              this.$set( this.columns[ index ], 'hidden', ! this.columns[ index ].hidden );
            },


            // load items is what brings back the rows from server
            loadAssessments() {
                this.$Progress.start();
                var parameters = "?perPage="+ this.serverParams.perPage;
                parameters = parameters + "&page="+ this.serverParams.page;
                parameters = parameters + "&sortField="+ this.serverParams.sort[0].field;
                parameters = parameters + "&sortType="+ this.serverParams.sort[0].type;
                parameters = parameters + "&searchTerm="+ this.serverParams.searchTerm;
                var url = "api/assessments"+parameters;
                //console.log(JSON.stringify( url));
                try{
                    this.form.get( url ).then( assessments  => {
                        if(assessments.data.data){
                          this.totalRecords = assessments.data.data.total
                          this.assessments = assessments.data.data.data;
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
            //console.log('Assessment Component mounted.')
            this.$Progress.start();
            this.loadAssessments();
            this.$Progress.finish();

        },


        created() {
            this.$Progress.start();
            this.loadAssessments();
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



