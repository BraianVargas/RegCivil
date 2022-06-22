<template>
    <div class="card-body">
        <div
            class="vx-row border border-primary"
            :key="index"
            v-for="(v, index) in $v.deliveryInfo.$each.$iter"
            style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
        >
            <div class="row">
                <div class="col-md-1">
                    <label for="document_type_id">Tipo</label>
                    <select
                        v-model.trim="v.retirement.document_type_id.$model"
                        :readonly="v.retirement.is_read_only.$model"
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
                        >Nro. de documento del retirante</label
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="v.retirement.document_number.$model"
                        @input="v.retirement.document_number.$touch()"
                        :readonly="v.retirement.is_read_only.$model"
                        v-on:keyup.enter="findPerson(v.retirement)"
                        value=""
                    />
                    <span
                        class="help-block"
                        v-if="
                            v.retirement.document_number.$error &&
                                !v.retirement.document_number.required
                        "
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="
                            v.retirement.document_number.$error &&
                                !v.retirement.document_number.numeric
                        "
                        >Solo números</span
                    >
                    <span
                        class="help-block"
                        v-if="
                            v.retirement.document_number.$error &&
                                !v.retirement.document_number.maxLength
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
                        @click="findPerson(v.retirement)"
                        :disabled="v.retirement.is_read_only.$model"
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
                        v-model.trim="v.retirement.is_applicant.$model"
                        v-on:change="onChangeApplicant(v.retirement)"
                    />
                    <label for="yes">Si</label>
                    <input
                        type="radio"
                        id="no"
                        value="0"
                        v-model.trim="v.retirement.is_applicant.$model"
                        v-on:change="onChangeApplicant(v.retirement)"
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
                        :readonly="v.retirement.is_read_only.$model"
                        v-model.trim="v.retirement.first_name.$model"
                        @input="v.retirement.first_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="
                            v.retirement.first_name.$error &&
                                !v.retirement.first_name.required
                        "
                        >Requerido</span
                    >
                </div>

                <div class="col-md-4">
                    <label for="last_name">Apellido</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        :readonly="v.retirement.is_read_only.$model"
                        v-model.trim="v.retirement.last_name.$model"
                        @input="v.retirement.last_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="
                            v.retirement.last_name.$error &&
                                !v.retirement.last_name.required
                        "
                        >Requerido</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";

import { validationMixin } from "vuelidate";
import { required, numeric, email, maxLength } from "vuelidate/lib/validators";
import moment from "moment";

export default {
    components: {
        Datepicker
    },
    data() {
        return {
            es: es,
            deliveryInfo: [
                {
                    retirement: {
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
        types_documents: Array,
        applicant: Object
    },
    mixins: [validationMixin],
    validations: {
        deliveryInfo: {
            $each: {
                retirement: {
                    person_id: { required },
                    document_type_id: { required },
                    document_number: { required, numeric, maxLength: maxLength(20) },
                    is_applicant: { required },
                    first_name: { required },
                    last_name: { required },
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
            this.$v.deliveryInfo.$touch();
            var isValid = !this.$v.deliveryInfo.$invalid;
            if (this.not_validate) {
                isValid = true;
            }
            this.$emit("on-validate", this.$data, isValid);
            return isValid;
        }
    },
    mounted() {
        this.$v.deliveryInfo.$reset();
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
