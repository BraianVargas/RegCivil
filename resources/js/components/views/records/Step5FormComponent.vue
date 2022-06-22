<template>
    <div class="card-body">
        <div
            class="vx-row border border-primary"
            style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
        >
            <div class="card card-info">
                <h5 class="card-header">Datos del solicitante</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <b>Nombre completo :</b>
                            {{ final_model.first_name }}
                            {{ final_model.last_name }}
                        </div>

                        <div class="col-md-6">
                            <b>Nro. documento:</b>
                            {{ getTypeDocument(final_model.document_type_id) }}
                            {{ final_model.document_number }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Sexo:</b>
                            {{ $func.getGender(final_model.gender) }}
                        </div>
                        <div class="col-md-6">
                            <b>Fecha de nacimiento:</b>
                            {{ customFormatter(final_model.birth_date) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Domicilio:</b> {{ final_model.address }}
                        </div>
                        <div class="col-md-4">
                            <b>Localidad:</b> {{ final_model.location }}
                        </div>
                        <div class="col-md-2">
                            <b>CP:</b> {{ final_model.postal_code }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <b>Teléfono:</b> {{ final_model.phone }}
                        </div>
                        <div class="col-md-4">
                            <b>Celular:</b> {{ final_model.cell_phone }}
                        </div>
                        <div class="col-md-4">
                            <b>Teléfono otro:</b>
                            {{ final_model.another_phone }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b>Tipo de trámite:</b>
                            {{
                                getTypeProcedure(final_model.type_procedure_id)
                            }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="final_model.type_procedure_id == 1"
                class="card card-info"
            >
                <h5 class="card-header">Datos del trámite</h5>
                <div class="card-body">
                    <div
                        class="vx-row border border-primary"
                        style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
                        :key="index"
                        v-for="(item, index) in final_model.proceedingsInfo"
                    >
                        <div class="row">
                            <div class="col-md-3">
                                <b>Tipo de partida:</b>
                                {{ getTypeProceeding(item.type_proceeding_id) }}
                            </div>
                            <div class="col-md-3">
                                <b>Tomo/Libro:</b> {{ item.tome_book }}
                            </div>
                            <div class="col-md-3">
                                <b>Oficina:</b>
                                {{ getOffice(item.office_id) }}
                            </div>
                            <div class="col-md-3">
                                <b>Nro. de copias:</b>
                                {{ item.number_copies }}
                            </div>
                        </div>
                        <div class="row"><br /><br /></div>
                        <div class="row">
                            <div class="col-md-12">
                                <b>Datos del Interesado</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <b>Nombre completo:</b>
                                {{ item.interested.first_name }} {{ item.interested.last_name }}
                            </div>
                            <div class="col-md-6">
                                <b>Nro. documento:</b>
                                {{ getTypeDocument(item.interested.document_type_id) }}
                                {{ item.interested.document_number }}
                            </div>
                        </div>
                        <div
                            v-if="
                                $variables.proceedingsWithSpouse.includes(
                                    item.type_proceeding_id
                                )
                            "
                            class="row"
                        >
                            <br /><br />
                        </div>
                        <div
                            v-if="
                                $variables.proceedingsWithSpouse.includes(
                                    item.type_proceeding_id
                                )
                            "
                            class="row"
                        >
                            <div class="col-md-12">
                                <b>Datos del Conyuge</b>
                            </div>
                        </div>
                        <div
                            v-if="
                                $variables.proceedingsWithSpouse.includes(
                                    item.type_proceeding_id
                                )
                            "
                            class="row"
                        >
                            <div class="col-md-6">
                                <b>Nombre completo:</b>
                                {{ item.spouse.first_name }}
                                {{ item.spouse.last_name }}
                            </div>
                            <div class="col-md-6">
                                <b>Nro. documento:</b>
                                {{
                                    getTypeDocument(
                                        item.spouse.document_type_id
                                    )
                                }}
                                {{ item.spouse.document_number }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="final_model.type_procedure_id == 1"
                class="card card-info"
            >
                <h5 class="card-header">Datos del retiro</h5>
                <div class="card-body">
                    <div
                        class="row"
                        :key="index"
                        v-for="(item, index) in final_model.deliveryInfo"
                    >
                        <div class="col-md-6">
                            <b>Nombre completo:</b>
                            {{ item.retirement.first_name }} {{ item.retirement.last_name }}
                        </div>
                        <div class="col-md-6">
                            <b>Nro. documento:</b>
                            {{ getTypeDocument(item.retirement.document_type_id) }}
                            {{ item.retirement.document_number }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-info">
                <h5 class="card-header">Datos del turno</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <b>Delegación:</b>
                            {{ final_model.delegation_id }}
                        </div>
                        <div class="col-md-6">
                            <b>Fecha:</b>
                            {{ customFormatter(final_model.turn_date) }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <b>Hora:</b>
                            {{ final_model.turn_time_selected }}
                        </div>
                        <div class="col-md-6">
                            <b>Duración:</b>
                            {{ final_model.turn_duration_selected }}
                        </div>
                    </div>
                </div>
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
            es: es
        };
    },
    props: {
        final_model: Object,
        offices: Array,
        types_documents: Array,
        types_proceedings: Array,
        types_procedures: Array
    },
    methods: {
        customFormatter(date) {
            return moment(date).format("DD-MM-YYYY");
        },
        getOffice(id) {
            if (typeof id == "undefined") {
                id = 1;
            }
            return this.offices.find(element => element.id == id).name;
        },
        getTypeDocument(id) {
            if (typeof id == "undefined") {
                id = 1;
            }
            return this.types_documents.find(element => element.id == id).name;
        },
        getTypeProceeding(id) {
            if (typeof id == "undefined") {
                id = 1;
            }
            return this.types_proceedings.find(element => element.id == id)
                .name;
        },
        getTypeProcedure(id) {
            if (typeof id == "undefined") {
                id = 1;
            }
            return this.types_procedures.find(element => element.id == id).name;
        },
        getDelegation(id) {
            if (typeof id == "undefined") {
                id = 1;
            }
            return this.delegations.find(element => element.id == id).name;
        }
    },
    mounted() {}
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
