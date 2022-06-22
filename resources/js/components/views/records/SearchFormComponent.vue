<template>
    <form v-on:submit.prevent>
        <div class="card-body">
            <div
                class="vx-row border border-primary"
                style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
            >
                <div class="row">
                    <div class="col-md-4">
                        <label for="delegation_id">Delegación</label>
                        <select
                            v-model="delegation_id"
                            class="form-control form-control-sm dropdown-toggle"
                            @change="getDelegation()"
                        >
                            <option
                                v-for="(item, index) in delegations"
                                v-bind:value="item.id"
                                :key="index"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="date_range">Rango de fechas</label>
                        <date-picker
                            :lang="lang"
                            v-model="date_range"
                            value-type="date"
                            :format="momentFormat"
                            input-class="form-control form-control-sm"
                            range
                        ></date-picker>

                        <span
                            class="help-block"
                            v-if="
                                $v.date_range.$error && !$v.date_range.numeric
                            "
                            >Solo números</span
                        >
                    </div>

                    <div class="col-md-4">
                        <label for="date_range"
                            >Descripción de la zona seleccionada:</label
                        ><br />

                        <b>Dirección:</b> {{ delegation.address }}<br />
                        <b>Zona:</b> {{ delegation.zone }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button
                            id="addBtn"
                            type="submit"
                            class="btn btn-info btn-sm"
                            @click="generateReport"
                        >
                            <i class="fas fa-calendar" aria-hidden="true"></i>
                            Generar informe
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <b-table
                    id="my-table"
                    :items="items"
                    :fields="fields_table"
                    :per-page="perPage"
                    :current-page="currentPage"
                    select-mode="single"
                    ref="selectableTable"
                    small
                    selectable
                >
                    <template #cell(turn_selected)="{ rowSelected }">
                        <template v-if="rowSelected">
                            <span aria-hidden="true">&check;</span>
                            <span class="sr-only">Seleccionado</span>
                        </template>
                        <template v-else>
                            <span aria-hidden="true">&nbsp;</span>
                            <span class="sr-only">No seleccionado</span>
                        </template>
                    </template>
                </b-table>

                <b-pagination
                    class="text-center"
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="my-table"
                ></b-pagination>
            </div>

            <div v-if="items.length > 0" class="row">
                <div class="col-md-12 text-right">
                    <download-excel
                        :data="items"
                        :name="filename"
                        :header="titles"
                        :fields="fields_excel"
                    >
                        <button type="submit" class="btn btn-info btn-sm">
                            <i class="fa fa-file-excel" aria-hidden="true"></i>
                            Descargar informe
                        </button>
                    </download-excel>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
//local registration
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import 'vue2-datepicker/locale/es';
import { validationMixin } from "vuelidate";
import { required, numeric, email } from "vuelidate/lib/validators";
import axios from "axios";
import moment from "moment";

//component code
export default {
    components: { DatePicker },
    props: {
        delegations: Array,
        range: Array
    },
    mixins: [validationMixin],
    validations: {
        delegation_id: { required },
        date_range: { required },
        form: ["delegation_id", "date_range"]
    },
    computed: {
        rows() {
            return this.items.length;
        },
        delegationSelected() {}
    },
    data() {
        return {
            lang: {
                formatLocale: {
                    firstDayOfWeek: 1
                },
                monthBeforeYear: false
            },
            filename: "data",
            delegation: [],
            delegation_id: 1,
            date_range: [new Date(), new Date()],
            momentFormat: "D-M-Y",
            currentPage: 1,
            perPage: 5,
            items: [],
            fields_excel: {
                Fecha: "date",
                Hora: "time",
                Duración: "duration",
                Apellido: "last_name",
                Nombre: "first_name",
                Documento: "document",
                Teléfono: "phone",
                Celular: "cell_phone",
                "Tipo de trámite": "type_procedure",
                Delegación: "delegetion",
                Dirección: "address"
            },
            titles: "Listado de turnos asignados",
            fields_table: [
                {
                    key: "date",
                    label: "Fecha",
                    sortable: true
                },
                {
                    key: "time",
                    label: "Hora",
                    sortable: true
                },
                {
                    key: "duration",
                    label: "Duración",
                    sortable: true
                },
                {
                    key: "last_name",
                    label: "Apellido",
                    sortable: true
                },
                {
                    key: "first_name",
                    label: "Nombre",
                    sortable: true
                },
                {
                    key: "document",
                    label: "Documento",
                    sortable: true
                },
                {
                    key: "phone",
                    label: "Teléfono",
                    sortable: true
                },
                {
                    key: "cell_phone",
                    label: "Celular",
                    sortable: true
                }
            ]
        };
    },
    methods: {
        getDelegation() {
            this.delegation = this.delegations.find(
                element => element.id == this.delegation_id
            );
        },
        generateReport() {
            this.$v.$touch();
            var isValid = !this.$v.$invalid;
            if (isValid) {
                this.getTurnsAssigned(this.delegation_id, this.date_range);
            }
        },
        getTurnsAssigned: function(id, range) {
            this.items = [];
            const fromDate = moment(range[0]).format("YYYY-MM-DD");
            const toDate = moment(range[1]).format("YYYY-MM-DD");
            axios
                .get(`/delegations/${id}/${fromDate}/${toDate}/turns`)
                .then(response => {
                    this.items = response.data.data;
                    this.filename = "listado_turnos_" + fromDate + "_" + toDate;
                    this.titles =
                        "Listado de turnos del " + fromDate + " al " + toDate;
                })
                .catch(error => {
                    // Display a error toast, with a title
                    toastr.error(
                        "No se ha encontrado fechas en esa delegación!",
                        "Error"
                    );
                });
        }
    },
    mounted() {
        this.date_range = [moment().toDate(), moment().toDate()];
        this.delegation = this.delegations[0];
        this.delegation_id = this.delegations[0].id;
    }
};
</script>
<style lang="scss" scoped>
.help-block {
    color: red;
    font-size: 12px;
}
</style>
