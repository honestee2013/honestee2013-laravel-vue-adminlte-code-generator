<template>
  <section class="content">
    <!-- PDF Generator begins -->
    <html-pdf :show-layout="false" :float-layout="true" :enable-download="true" :preview-modal="true"
      :paginate-elements-by-height="14000" filename="code_lists" :pdf-quality="2" :manual-pagination="true"
      pdf-format="a4" pdf-orientation="landscape" pdf-content-width="100%" ref="html2Pdf">
      <section id="printPaper" slot="pdf-content"
        style=" width:100%; background-color: white;  padding: 0% 0.5% 40% 0.5%;">
        <div style="margin-left: 0; width: 100%; ">
          <h3 style="text-align:center; text-decoration: underline; padding: 1em; "> Code Lists</h3>
          <table class="table table-bordered" style="width: 100%; ">
            <thead>
              <tr>
                <th>Code type</th>
                <th>Use for</th>
                <th>Expiry date</th>
                <th>Number of use</th>
                <th>Used by</th>
                <th>Maximum use</th>
                <th>Value</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(code, index) in codes" :key="code.id">
                <td>{{ code.code_type }} </td>
                <td>{{ code.use_for }} </td>
                <td>{{ code.expiry_date }} </td>
                <td>{{ code.number_of_use }} </td>
                <td>{{ code.used_by }} </td>
                <td>{{ code.maximum_use }} </td>
                <td>{{ code.value }} </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </html-pdf>
    <!-- PDF Generator Ends -->

    <!-- Container Begins -->
    <div class="container-fluid">
      <!-- Row begins-->
      <div class="row">
        <!-- Card Begins (Only Admin can see this content) -->
        <div class="card w-100" v-if="$gate.isAdmin()">
          <!-- card header -->
          <div class="card-header pr-sm-3">
            <div class="d-flex mb-3">
              <h3 class="card-title mr-auto ">Code List </h3>
              <button type="button" class="btn btn-sm btn-primary " @click="newModal">
                <i class="fa fa-plus-square"></i>
                Add New
              </button>
            </div>
          </div> <!-- /.card header -->

          <!-- card-body table container -->
          <div class="card-body table-responsive p-2">
            <!-- VUE GOOD TABLE BEGINS -->
            <vue-good-table mode="remote" @on-page-change="onPageChange" @on-sort-change="onSortChange"
              @on-column-filter="onColumnFilter" @on-per-page-change="onPerPageChange" @on-search="onSearch"
              @on-selected-rows-change="onSelectionChanged" styleClass="vgt-table  bordered table-hover "
              :totalRows="totalRecords" :isLoading.sync="isLoading" :select-options="{
                enabled: true,
              }" :pagination-options="{
                        enabled: true,
                        perPageDropdown: [5, 10, 20, 50, 75, 100],
                        dropdownAllowAll: false,
                      }" :search-options="{
                        enabled: true,
                        placeholder: 'Search the table',
                      }" :rows="codes" :columns="columns">
              <!-- Vue Good TABLE CONTENTS and ACTIONS slot -->
              <div slot="table-actions">
                <!-- Button Groups for EXPORTING TABLE -->
                <div class="mr-auto btn-group my-1" role="group" aria-label="Button group with nested dropdown">
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-default btn-sm " data-toggle="dropdown"
                      aria-expanded="false">
                      <i class="fa fa-download"></i> Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <button href="#" class="dropdown-item" @click.prevent="print">
                        <i class="fa fa-print mr-1"></i> Print
                      </button>
                      <button href="#" class="dropdown-item">
                        <!-- JSON_EXCEL Component -->
                        <json-excel class="" :data="codes" :fields="table_heders" worksheet="Code Lits"
                          name="code_lists.xls">
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
                    <button id="btnGroupDrop1" type="button" class="btn btn-default btn-sm " data-toggle="dropdown"
                      aria-expanded="false">
                      <i class="fa fa-eye"></i> Show
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <li v-for="(column, index) in columns" :key="index">
                        <a href="#" class="dropdown-item" tabIndex="-1"
                          @click.prevent="toggleColumn( index, $event )"><input :checked="!column.hidden"
                            type="checkbox" />&nbsp;&nbsp;{{column.label}}</a>
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
                    <button type="button" class="btn btn-sm btn-primary" @click="codeDetail(props)">Detail</button>
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split"
                      data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>

                    <div class="dropdown-menu">
                      <!--<a class="dropdown-item" href="#" @click="codeDetail('show')"><i class="fa fa-eye"> <span style="margin-left:0.1em"> Details </span> </i></a>
                                  <div class="dropdown-divider"></div>-->
                      <a class="dropdown-item" href="#" @click="editModal(props.row)"><i class="fa fa-edit"> <span
                            style="margin-left:0.1em"> Edit </span> </i></a>
                      <a class="dropdown-item " href="#" @click="deleteCode(props.row.id)"><i class="fa fa-trash"> <span
                            style="margin-left:0.1em"> Delete </span> </i></a>
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
                  <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown"
                    aria-expanded="false">
                    Selected Codes
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="#" @click="deleteSelectedCodes()"><i class="fa fa-trash"> <span
                          style="margin-left:0.1em"> Delete </span> </i></a>
                  </div>
                </div>
              </div>
            </vue-good-table>
          </div> <!-- card-body table container ends -->

          <div class="card-footer">
            <!--<pagination :data="codes" @pagination-change-page="getResults"></pagination>-->
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
          <div class="modal-header">
            <!-- Modal Header -->
            <h5 class="modal-title" v-show="!editmode">Generate new code</h5>
            <h5 class="modal-title" v-show="editmode">Update Code</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!-- <form @submit.prevent="createModel"> -->
          <form @submit.prevent="editmode ? updateCode() : createCode()">
            <div class="modal-body">
              <div class="form-group">

                <input type="hidden" v-model="form.id"></input>

              </div>
              <div class="form-group">
                <label>User type</label>
                <select v-model="form.role_id" name="role_id" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'role_id' ) }">
                  <option v-for="(item, index)  in roles" :key="index" :value="item.id"> 
                      {{ (item.name.charAt(0).toUpperCase() + item.name.slice(1)).replaceAll("-", " ") }} 
                  </option>
                </select>
                <has-error :form="form" field="role_id"></has-error>

              </div>
              <div class="form-group">
                <label>Code type</label>
                <select v-model="form.code_type" name="type" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'type' ) }">
                  <option value="Pin">Pin</option>
                  <option value="Token">Token</option>
                </select>
                <has-error :form="form" field="type"></has-error>

              </div>
              <div class="form-group">
                <label>Use for</label>
                <select v-model="form.use_for" name="use" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'use' ) }">
                  <option> User registration </option>
                  <option> Student result check </option>
                </select>
                <has-error :form="form" field="use"></has-error>

              </div>
              <div class="form-group">
                <label>Expiry time</label>
                <select v-model="form.expire_time" name="expire" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'expire' ) }">
                  <option> After one week </option>
                  <option> After one month </option>
                </select>
                <has-error :form="form" field="expire"></has-error>

              </div>
              <div class="form-group">
                <label>Max no. of use</label>
                <select v-model="form.maximum_use" name="maximum_use" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'number_of_use' ) }">
                  <option value="1">One time</option>
                  <option value="3">Three times</option>
                  <option value="5">Five times</option>
                </select>
                <has-error :form="form" field="number_of_use"></has-error>

              </div>
              <div class="form-group" v-show="editmode">
                <label>Used by (User number)</label>
                <input type="text" v-model="form.used_by" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'used_by' ) }"></input>
              </div>
              <div class="form-group" v-show="editmode">
                <label>Used times</label>
                <input type="number" v-model="form.number_of_use" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'used_times' ) }"></input>
              </div>
              <div class="form-group" v-show="editmode">
                <label>Value</label>
                <input type="text" v-model="form.value" name="value" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'value' ) }" maxlength="255">
                <has-error :form="form" field="value"></has-error>
              </div>



              <div class="form-group" v-show="!editmode">
                <label>Quantity</label>
                <select v-model="form.quantity" name="number_of_use" class="form-control"
                  :class="{ 'is-invalid': form.errors.has( 'quantity' ) }">
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option  value="50">50</option>
                  <option value="100">100</option>
                  <option value="200">200</option>
                  <option value="300">300</option>
                  <option value="500">500</option>
                </select>
                <has-error :form="form" field="quantity"></has-error>

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
    <div class="modal fade" id="codeDetail" tabindex="-1" role="dialog" aria-labelledby="codeDetail" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <!-- Modal Header -->
            <h5 class="modal-title"> Code Detail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table">
              <tr class="row" v-for="(item, key, index) in clickedRow" v-if="!isSpecialColumn(key)">
                <th class="text-primary col-4" style="text-align: right; margin-left:0em"> {{ ucAllWords(key) }} </th>
                <td class="col-8"> {{ item }} </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteCode(clickedRow.id)"><i
                class="fa fa-trash"></i> Delete </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" @click="editModal(clickedRow)"><i
                class="fa fa-edit"></i> Edit</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>
              Close</button>

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
  data() {
    return {
      editmode: false,
      codes: [],
      search: '',

      isLoading: false,
      totalRecords: 0,
      clickedRow: null,
      selectedRows: [],


      roles: [],

      serverParams: {
        columnFilters: {
        },
        sort: [

          {
            "type": "asc",
            "field": "code_type"
          },
          {
            "type": "asc",
            "field": "use_for"
          },
          {
            "type": "asc",
            "field": "user_type"
          },
          {
            "type": "asc",
            "field": "expire_time"
          },
          {
            "type": "asc",
            "field": "number_of_use"
          },
          {
            "type": "asc",
            "field": "used_by"
          },
          {
            "type": "asc",
            "field": "maximum_use"
          },
          {
            "type": "asc",
            "field": "value"
          },
        ],
        page: 1,
        perPage: 5,
        searchTerm: '',
      },

      form: new Form({
        "id": "",
        "role_id": 9,
        "code_type": "Token",
        "use_for": "User registration",
        "expire_time": "After one week",
        "maximum_use": 5,
        "quantity": 5,
        "used_by": "",
        "number_of_use" : "",
        "value": "",
        "created_at": "",
        "updated_at": "",
      }),

      table_heders: {
        "Code type": "code_type",
        "Use for": "use_for",
        "User type": "user_type",
        "Expire time": "expire_time",
        "Expiry date": "expiry_date",
        "Number Of Use": "number_of_use",
        "Used By": "used_by",
        "Maximum use": "maximum_use",
        "Value": "value",
      },

      columns: [


        {
          label: "Code type",
          field: "code_type",
          hidden: false
        },
        {
          label: "Use for",
          field: "use_for",
          hidden: false
        },
        {
          label: "User type",
          field: "user_type",
          hidden: false
        },
        {
          label: "Expire time",
          field: "expire_time",
          hidden: false
        },
        {
          label: "Expiry date",
          field: "expiry_date",
          hidden: false
        },
        {
          label: "Maximum  use",
          field: "maximum_use",
          hidden: false
        },
        {
          label: "Used By",
          field: "used_by",
          hidden: false
        },
        {
          label: "Number of use",
          field: "number_of_use",
          hidden: false
        },
        {
          label: "Value",
          field: "value",
          hidden: false
        },
        {
          label: "Created At",
          field: "created_at",
          hidden: true
        },
        {
          label: "Updated At",
          field: "updated_at",
          hidden: true
        },

        {
          label: 'Actions',
          field: 'action',
          sortable: false,

        },

      ],

    };
  },


  components: {
    "json-excel": JsonExcel,
    "html-pdf": VueHtml2pdf,
  },


  methods: {

    codeDetail(params) {
      this.clickedRow = params.row;
      $('#codeDetail').modal('show');
    },

    updateCode() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form.put('api/codes/' + this.form.id)
        .then((response) => {
          // success
          $('#addNew').modal('hide');
          Toast.fire({
            icon: 'success',
            title: response.data.message
          });
          this.$Progress.finish();
          //  Fire.$emit('AfterCreate');
          this.loadCodes();
        })
        .catch(() => {
          Toast.fire({
            icon: 'error',
            title: 'Some error occured!'
          });
          this.$Progress.fail();
        });
    },

    editModal(code) {
      this.editmode = true;
      this.form.reset();
      $('#addNew').modal('show');
      this.form.fill(code);
    },

    newModal() {
      this.editmode = false;
      this.form.reset();
      $('#addNew').modal('show');
    },

    deleteCode(id) {
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
          this.form.delete('api/codes/' + JSON.stringify(theData)).then(() => {
            Swal.fire(
              'Deleted!',
              'The code was deleted successfully.',
              'success'
            );
            // Fire.$emit('AfterCreate');
            this.loadCodes();
          }).catch((data) => {
            Swal.fire("Failed!", data.message, "warning");
          });
        }
      })
    },

    deleteSelectedCodes() {
      Swal.fire({
        title: 'Are you sure?',
        text: "Delete " + this.selectedRows.length + " records? You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          let theData = JSON.stringify(this.selectedRows);
          this.form.delete('api/codes/' + theData).then(() => {
            Swal.fire(
              'Deleted!',
              'The code was deleted successfully.',
              'success'
            );
            // Fire.$emit('AfterCreate');
            this.loadCodes();
          }).catch((data) => {
            Swal.fire("Failed!", data.message, "warning");
          });
        }
      })
    },


    createCode() {
      this.form.post('api/codes')
        .then((response) => {
          $('#addNew').modal('hide');
          Toast.fire({
            icon: 'success',
            title: response.data.message
          });
          this.$Progress.finish();
          this.loadCodes();
        })
        .catch(() => {
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

    print() {
      this.$Progress.start();
      this.$htmlToPaper('printPaper');
      this.$Progress.finish();
    },


    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },


    onSelectionChanged(params) {
      this.selectedRows = [];
      for (var i = 0; i < params.selectedRows.length; i++) {
        if (params.selectedRows[i].vgtSelected)
          this.selectedRows[i] = params.selectedRows[i].id;
      }
    },


    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.loadCodes();
    },

    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.loadCodes();
    },

    onSortChange(params) {
      var sortType = params[0].type;
      if (sortType != 'asc' && sortType != 'desc')
        sortType = 'asc';
      this.updateParams({
        sort: [{
          type: sortType,
          field: params[0].field,
        }],
      });
      this.loadCodes();
    },


    onColumnFilter(params) {
      this.updateParams(params);
      this.loadCodes();
    },


    onSearch(params) {
      this.updateParams({ searchTerm: params.searchTerm });
      this.loadCodes();
    },


    toggleColumn(index, event) {
      // Set hidden to inverse of what it currently is
      this.$set(this.columns[index], 'hidden', !this.columns[index].hidden);
    },


    // load items is what brings back the rows from server
    loadCodes() {
      this.$Progress.start();
      var parameters = "?perPage=" + this.serverParams.perPage;
      parameters = parameters + "&page=" + this.serverParams.page;
      parameters = parameters + "&sortField=" + this.serverParams.sort[0].field;
      parameters = parameters + "&sortType=" + this.serverParams.sort[0].type;
      parameters = parameters + "&searchTerm=" + this.serverParams.searchTerm;
      var url = "api/codes" + parameters;
      //console.log(JSON.stringify( url));
      try {
        this.form.get(url).then(codes => {
          if (codes.data.data) {
            this.totalRecords = codes.data.data.total
            this.codes = codes.data.data.data;
          }
        });
      } catch (error) {
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


    isSpecialColumn(field) {
      if (field.indexOf('id') < 0 && field != 'updated_at' && field != 'created_at'
        && field != 'vgt_id' && field != 'vgtSelected' && field != 'originalIndex')
        return false;
      else
        return true;
    },


    loadRoles() {


      try {
        var url = "api/roles?all=all";
        axios.get(url).then(roles => {
          if (roles.data.data) {
            this.roles = roles.data.data;
          }
        });
      } catch (error) {
        console.log(error.message);
      };

    },




  },


  mounted() {
    //console.log('Code Component mounted.')
    this.$Progress.start();
    this.loadCodes();
    this.loadRoles();
    this.$Progress.finish();

  },


  created() {
    this.$Progress.start();
    this.loadCodes();
    this.$Progress.finish();

  },


  computed: {},


}


</script>

<style>
@media screen and (min-width: 990px) {
  #printPaper {
    margin-left: -22.2%;
    padding: 0% 1% 3% 1%;
    width: 100%;
  }
}
</style>



