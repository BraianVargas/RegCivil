<template>
    <div class="card-body">
        <div
            class="vx-row border border-primary"
            :key="index"
            v-for="(v, index) in $v.proceedingsInfo.$each.$iter"
            style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
        >
            <div class="row">
                <div class="col-md-3">
                    <label for="type_proceeding_id">Tipo de partida</label>
                    <select
                        v-model.trim="v.type_proceeding_id.$model"
                        class="form-control form-control-sm dropdown-toggle"
                    >
                        <option
                            v-for="(item, index) in types_proceedings"
                            v-bind:value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tome_book">Tomo/Libro</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="v.tome_book.$model"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="v.tome_book.$error && !v.tome_book.required"
                        >Requerido</span
                    >
                </div>

                <div class="col-md-3">
                    <label for="office_id">Oficina</label>
                    <select
                        v-model.trim="v.office_id.$model"
                        class="form-control form-control-sm dropdown-toggle"
                    >
                        <option
                            v-for="(item, index) in offices"
                            v-bind:value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="number_copies">Copias</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="v.number_copies.$model"
                        value=""
                    />

                    <span class="help-block" v-if="!v.number_copies.required"
                        >Requerido</span
                    >
                </div>
            </div>
            <div class="row"><hr size="10" /></div>
            <div class="row">
                <div class="col-md-1">
                    <label for="document_type_id">Tipo</label>
                    <select
                        v-model.trim="v.interested.document_type_id.$model"
                        :readonly="v.interested.is_read_only.$model"
                        class="form-control form-control-sm dropdown-toggle"
                    >
                        <option
                            v-for="(item, index) in types_documents"
                            v-bind:value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="document_number"
                        >Nro. de documento del interesado</label
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="v.interested.document_number.$model"
                        @input="v.interested.document_number.$touch()"
                        :readonly="v.interested.is_read_only.$model"
                        v-on:keyup.enter="findPerson(v.interested)"
                        value=""
                    />
                    <span
                        class="help-block"
                        v-if="
                            v.interested.document_number.$error &&
                                !v.interested.document_number.required
                        "
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="
                            v.interested.document_number.$error &&
                                !v.interested.document_number.numeric
                        "
                        >Solo números</span
                    >

                    <span
                        class="help-block"
                        v-if="
                            v.interested.document_number.$error &&
                                !v.interested.document_number.maxLength
                        "
                        >Longitud máxima de 20 números</span
                    >
                </div>

                <div class="col-md-4 text-left ">
                    <br />
                    <button
                        id="searchBtn"
                        type="submit"
                        class="btn btn-info btn-sm"
                        @click="findPerson(v.interested)"
                        :disabled="v.interested.is_read_only.$model"
                    >
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <label for="is_applicant">Es solicitante?</label><br />
                    <input
                        type="radio"
                        id="yes"
                        value="1"
                        v-on:change="onChangeApplicant(v.interested)"
                        v-model="v.interested.is_applicant.$model"
                    />
                    <label for="yes">Si</label>
                    <input
                        type="radio"
                        id="no"
                        value="0"
                        v-on:change="onChangeApplicant(v.interested)"
                        v-model="v.interested.is_applicant.$model"
                    />
                    <label for="no">No</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="first_name">Nombre</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="v.interested.first_name.$model"
                        :readonly="v.interested.is_read_only.$model"
                        @input="v.interested.first_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="
                            v.interested.first_name.$error &&
                                !v.interested.first_name.required
                        "
                        >Requerido</span
                    >
                </div>

                <div class="col-md-4">
                    <label for="last_name">Apellido</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="v.interested.last_name.$model"
                        :readonly="v.interested.is_read_only.$model"
                        @input="v.interested.last_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="
                            v.interested.last_name.$error &&
                                !v.interested.last_name.required
                        "
                        >Requerido</span
                    >
                </div>
            </div>

            <div v-show="v.type_proceeding_id.$model == 2" class="row">
                <div class="col-md-1">
                    <label for="spouse_document_type_id">Tipo</label>
                    <select
                        v-model.trim="v.spouse.document_type_id.$model"
                        :readonly="v.spouse.is_read_only.$model"
                        class="form-control form-control-sm dropdown-toggle"
                    >
                        <option
                            v-for="(item, index) in types_documents"
                            v-bind:value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="spouse_document_number"
                        >Nro. de documento del conyuge</label
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        :readonly="v.spouse.is_read_only.$model"
                        v-model.trim="v.spouse.document_number.$model"
                        v-on:keyup.enter="findPerson(v.spouse)"
                        value=""
                    />
                    <span
                        class="help-block"
                        v-if="
                            v.spouse.document_number.$error &&
                                !v.spouse.document_number.required
                        "
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="
                            v.spouse.document_number.$error &&
                                !v.spouse.document_number.numeric
                        "
                        >Solo números</span
                    >

                    <span
                        class="help-block"
                        v-if="
                            v.spouse.document_number.$error &&
                                !v.spouse.document_number.maxLength
                        "
                        >Longitud máxima de 20 números</span
                    >
                </div>

                <div class="col-md-4 align-middle text-left ">
                    <br />
                    <button
                        id="searchBtn"
                        type="submit"
                        class="btn btn-info btn-sm"
                        @click="findPerson(v.spouse)"
                        :disabled="v.spouse.is_read_only.$model"
                    >
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <label for="is_applicant">Es solicitante?</label><br />
                    <input
                        type="radio"
                        id="yes"
                        value="1"
                        v-on:change="onChangeApplicant(v.spouse)"
                        v-model.trim="v.spouse.is_applicant.$model"
                    />
                    <label for="yes">Si</label>
                    <input
                        type="radio"
                        id="no"
                        value="0"
                        v-on:change="onChangeApplicant(v.spouse)"
                        v-model.trim="v.spouse.is_applicant.$model"
                    />
                    <label for="no">No</label>
                </div>
            </div>
            <div v-show="v.type_proceeding_id.$model == 2" class="row">
                <div class="col-md-4">
                    <label for="first_name">Nombre</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        :readonly="v.spouse.is_read_only.$model"
                        v-model.trim="v.spouse.first_name.$model"
                        @input="v.spouse.first_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="
                            v.spouse.first_name.$error &&
                                !v.spouse.first_name.required
                        "
                        >Requerido</span
                    >
                </div>

                <div class="col-md-4">
                    <label for="last_name">Apellido</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        :readonly="v.spouse.is_read_only.$model"
                        v-model.trim="v.spouse.last_name.$model"
                        @input="v.spouse.last_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="
                            v.spouse.last_name.$error &&
                                !v.spouse.last_name.required
                        "
                        >Requerido</span
                    >
                </div>
            </div>
            <div class="row" v-if="index > 0">
                <div class="col-md-12 text-right">
                    <button
                        id="addBtn"
                        type="submit"
                        class="btn btn-info btn-sm"
                        @click="deleteProceeding(index)"
                    >
                        <i class="fas fa-trash" aria-hidden="true"></i>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <button
                id="addBtn"
                type="submit"
                class="btn btn-info btn-sm"
                @click="addNewProceeding"
            >
                <i class="fas fa-plus" aria-hidden="true"></i>
                Agregar
            </button>
        </div>
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
import axios from "axios";
import { validationMixin } from "vuelidate";
import {
    required,
    numeric,
    email,
    requiredIf,
    maxLength
} from "vuelidate/lib/validators";
import moment from "moment";

export default {
    components: {
        Datepicker
    },
    data() {
        return {
            es: es,
            proceedingsInfo: [
                {
                    type_proceeding_id: 1,
                    office_id: 1,
                    tome_book: "",
                    number_copies: 1,
                    record_date: new Date(),
                    interested: {
                        person_id: 0,
                        document_type_id: 1,
                        document_number: "",
                        first_name: "",
                        last_name: "",
                        is_applicant: "0",
                        is_read_only: false,
                        gender: "1"
                    },
                    spouse: {
                        person_id: 0,
                        document_type_id: 1,
                        document_number: "",
                        first_name: "",
                        last_name: "",
                        is_applicant: "0",
                        is_read_only: false,
                        gender: "1"
                    }
                }
            ]
        };
    },
    props: {
        types_proceedings: Array,
        types_documents: Array,
        offices: Array,
        applicant: Object,
        not_validate: Boolean
    },
    mixins: [validationMixin],
    validations: {
        proceedingsInfo: {
            $each: {
                type_proceeding_id: { required },
                tome_book: { required },
                office_id: { required },
                number_copies: { required, numeric },
                interested: {
                    person_id: { numeric },
                    document_type_id: { required },
                    document_number: {
                        required,
                        numeric,
                        maxLength: maxLength(20)
                    },
                    first_name: { required },
                    last_name: { required },
                    is_applicant: { required },
                    is_read_only: { required },
                    gender: { required }
                },
                spouse: {
                    person_id: { numeric },
                    document_type_id: { required },
                    document_number: {
                        required: requiredIf(function(nestedModel) {
                            return this.$variables.proceedingsWithSpouse.includes(
                                nestedModel.type_proceeding_id
                            );
                        }),
                        numeric,
                        maxLength: maxLength(20)
                    },
                    first_name: {
                        required: requiredIf(function(nestedModel) {
                            return this.$variables.proceedingsWithSpouse.includes(
                                nestedModel.type_proceeding_id
                            );
                        })
                    },
                    last_name: {
                        required: requiredIf(function(nestedModel) {
                            return this.$variables.proceedingsWithSpouse.includes(
                                nestedModel.type_proceeding_id
                            );
                        })
                    },
                    is_applicant: { required },
                    is_read_only: { required },
                    gender: { required }
                }
            }
        }
    },
    methods: {
        onChangeApplicant(item) {
            if (item.is_applicant.$model == 1) {
                item.is_read_only.$model = true;
                item.person_id.$model = this.applicant.person_id;
                item.document_type_id.$model = this.applicant.document_type_id;
                item.document_number.$model = this.applicant.document_number;
                item.first_name.$model = this.applicant.first_name;
                item.last_name.$model = this.applicant.last_name;
                item.gender.$model = this.applicant.gender;
            } else {
                item.is_read_only.$model = false;
                item.person_id.$model = 0;
                item.document_type_id.$model = 1;
                item.document_number.$model = "";
                item.first_name.$model = "";
                item.last_name.$model = "";
                item.gender.$model = "1";
            }
        },
        deleteProceeding(index) {
            this.proceedingsInfo.splice(index, 1);
        },
        addNewProceeding() {
            var today = new Date();
            var info = {
                type_proceeding_id: 1,
                interested: {
                    person_id: 0,
                    document_type_id: 1,
                    document_number: "",
                    first_name: "",
                    last_name: "",
                    is_applicant: "0",
                    is_read_only: false,
                    gender: "1"
                },
                office_id: 1,
                tome_book: "",
                number_copies: 1,
                record_date: new Date(),
                spouse: {
                    person_id: 0,
                    document_type_id: 1,
                    document_number: "",
                    first_name: "",
                    last_name: "",
                    is_applicant: "0",
                    is_read_only: false,
                    gender: "1"
                }
            };
            this.proceedingsInfo.push(info);
        },
        findPerson(item) {
            if (item.$model.document_number) {
                axios
                    .get(
                        `/persons/${item.$model.document_type_id}/${item.$model.document_number}`
                    )
                    .then(response => {
                        var result = response.data.data;
                        item.$model.person_id = result.id;
                        item.$model.first_name = result.first_name;
                        item.$model.last_name = result.last_name;
                        item.$model.gender = result.gender;
                    })
                    .catch(error => {
                        item.$model.person_id = 0;
                        item.$model.first_name = "";
                        item.$model.last_name = "";
                        item.$model.gender = "1";
                        // Display a error toast, with a title
                        toastr.error(
                            "No se ha encontrado la persona!",
                            "Error"
                        );
                    });
            } else {
                // Display a error toast, with a title
                toastr.error("Debe ingresar un nro. de documento!", "Error");
            }
        },
        customFormatter(date) {
            return moment(date).format("DD-MM-YYYY");
        },
        validate() {
            var isValid;
            if (this.not_validate) {
                alert("SSSS");
                isValid = true;
            } else {
                this.$v.proceedingsInfo.$touch();
                isValid = !this.$v.proceedingsInfo.$invalid;
            }
            this.$emit("on-validate", this.$data, isValid);
            return isValid;
        }
    },
    mounted() {
        this.$v.proceedingsInfo.$reset();
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
