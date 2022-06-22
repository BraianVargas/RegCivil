<template>
    <div class="card-body">
        <div
            class="vx-row border border-primary"
            style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
        >
            <div class="row">
                <div class="col-md-3">
                    <label for="delegation_id">Delegación</label>
                    <select
                        v-model="delegation_id"
                        class="form-control form-control-sm dropdown-toggle"
                        @change="onChangeDelegation()"
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

                <div class="col-md-3">
                    <label for="birth_date">Fecha</label>
                    <datepicker
                        :language="es"
                        v-model="turn_date"
                        :format="customFormatter"
                        :disabled-dates="disabledDates"
                        @closed="onClosedDate"
                        input-class="form-control form-control-sm"
                    ></datepicker>

                    <span
                        class="help-block"
                        v-if="$v.turn_date.$error && !$v.turn_date.required"
                        >Requerido</span
                    >
                </div>
            </div>
            <div class="row"><hr /></div>
            <div class="row text-center">
                <span
                    class="help-block"
                    v-if="
                        $v.turn_id_selected.$error &&
                            !$v.turn_id_selected.required
                    "
                    >Requerido</span
                >
                <b-table
                    id="my-table"
                    :items="items"
                    :fields="fields"
                    :per-page="perPage"
                    :current-page="currentPage"
                    select-mode="single"
                    @row-selected="onRowSelected"
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
        </div>
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
import axios from "axios";
import { validationMixin } from "vuelidate";
import { required, numeric, email } from "vuelidate/lib/validators";
import moment from "moment";

export default {
    components: {
        Datepicker
    },
    data() {
        return {
            es: es,
            delegation_id: "",
            turn_selected: [],
            turn_id_selected: "",
            turn_time_selected: "",
            turn_duration_selected: "",
            turn_date: new Date(),
            disabledDates: {
                to: new Date(),
                from: new Date(),
                days: [6, 0]
            },
            currentPage: 1,
            perPage: 5,
            items: [],
            fields: [
                {
                    key: "id",
                    label: "Id",
                    sortable: true
                },
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
                }
            ]
        };
    },
    computed: {
        rows() {
            return this.items.length;
        }
    },
    props: {
        delegations: Array
    },
    // mixins: [validationMixin],
    validations: {
        turn_id_selected: { required },
        turn_date: { required },
        turn_time_selected: { required },
        turn_duration_selected: { required },
        form: [
            "turn_date",
            "turn_id_selected",
            "turn_time_selected",
            "turn_duration_selected"
        ]
    },
    methods: {
        onRowSelected(items) {
            this.turn_selected = items;
            if (this.turn_selected == "") {
                this.turn_id_selected = "";
                this.turn_time_selected = "";
                this.turn_duration_selected = "";
            } else {
                this.turn_id_selected = this.turn_selected[0].id;
                this.turn_time_selected = this.turn_selected[0].time;
                this.turn_duration_selected = this.turn_selected[0].duration;
            }
        },
        onClosedDate() {
            this.getTurns(this.delegation_id, this.turn_date);
        },
        onChangeDelegation() {
            this.getDates(this.delegation_id);
        },
        getDates: function(id) {
            axios
                .get(`/delegations/${id}/dates`)
                .then(response => {
                    var item = response.data.data;

                    this.disabledDates.from = moment(item)
                        .add(1, "days")
                        .toDate();
                })
                .catch(error => {
                    // Display a error toast, with a title
                    toastr.error(
                        "No se ha encontrado fechas en esa delegación!",
                        "Error"
                    );
                });
        },
        getTurns: function(id, date) {
            var new_date = moment(date).format("YYYY-MM-DD");
            axios
                .get(`/delegations/${id}/${new_date}/turns`)
                .then(response => {
                    this.items = response.data.data;
                })
                .catch(error => {
                    // Display a error toast, with a title
                    toastr.error(
                        "No se ha encontrado fechas en esa delegación!",
                        "Error"
                    );
                });
        },
        customFormatter(date) {
            return moment(date).format("DD-MM-YYYY");
        },
        validate() {
            this.$v.form.$touch();
            var isValid = !this.$v.form.$invalid;
            this.$emit("on-validate", this.$data, isValid);
            return isValid;
        }
    },
    mounted() {
        console.log(this.delegations);
        // if (this.delegations) {
        //     this.delegation_id = this.delegations[0].id;
        // }
        // this.getDates(this.delegation_id);
    }
};
</script>

<style lang="scss">
.help-block {
    color: red;
    font-size: 12px;
}
.vue-form-wizard .wizard-tab-content {
    min-height: 100px;
    padding: 10px 20px 10px !important;
}
</style>
