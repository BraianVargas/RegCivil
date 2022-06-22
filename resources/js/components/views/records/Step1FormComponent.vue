<template>
    <div class="card-body">
        <div
            class="vx-row border border-primary"
            style="border: 1px solid;border-radius: 16px;padding: 20px; margin-bottom: 20px;"
        >
            <div class="row">
                <div class="col-md-1">
                    <label for="document_type_id">Tipo</label>
                    <select
                        v-model="document_type_id"
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
                        >Nro. de documento del solicitante</label
                    >
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="document_number"
                        @input="$v.document_number.$touch()"
                        v-on:keyup.enter="findPerson"
                        value=""
                    />
                    <span
                        class="help-block"
                        v-if="
                            $v.document_number.$error &&
                                !$v.document_number.required
                        "
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="
                            $v.document_number.$error &&
                                !$v.document_number.numeric
                        "
                        >Solo números</span
                    >

                    <span
                        class="help-block"
                        v-if="
                            $v.document_number.$error &&
                                !$v.document_number.maxLength
                        "
                        >Longitud máxima de 20 números</span
                    >
                </div>

                <div class="col-md-6 text-left ">
                    <br />
                    <button
                        id="searchBtn"
                        type="submit"
                        class="btn btn-info btn-sm"
                        @click="findPerson"
                    >
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Buscar
                    </button>
                </div>
            </div>
            <div class="row">
                <hr />
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="first_name">Nombre</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="first_name"
                        @input="$v.first_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="$v.first_name.$error && !$v.first_name.required"
                        >Requerido</span
                    >
                </div>

                <div class="col-md-4">
                    <label for="last_name">Apellido</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="last_name"
                        @input="$v.last_name.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="$v.last_name.$error && !$v.last_name.required"
                        >Requerido</span
                    >
                </div>

                <div class="col-md-2">
                    <label for="gender">Sexo</label>
                    <select
                        v-model.trim="gender"
                        class="form-control input-sm form-control-sm"
                    >
                        <option
                            v-for="(item, index) in $variables.genders"
                            v-bind:value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="birth_date">Fecha de nacimiento</label>
                    <datepicker
                        :language="es"
                        v-model="birth_date"
                        name="birth_date"
                        :format="customFormatter"
                        input-class="form-control form-control-sm"
                    ></datepicker>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="address">Domicilio</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="address"
                        @input="$v.address.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="$v.address.$error && !$v.address.required"
                        >Requerido</span
                    >
                </div>

                <div class="col-md-3">
                    <label for="location">Localidad</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="location"
                        @input="$v.location.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="$v.location.$error && !$v.location.required"
                        >Requerido</span
                    >
                </div>

                <div class="col-md-1">
                    <label for="postal_code">CP</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="postal_code"
                        @input="$v.postal_code.$touch()"
                        value=""
                    />
                </div>

                <div class="col-md-4">
                    <label for="email">E-mail</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="email"
                        @input="$v.email.$touch()"
                        value=""
                    />

                    <span
                        class="help-block"
                        v-if="$v.email.$error && !$v.email.required"
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="$v.email.$error && !$v.email.email"
                        >Debe ser formato e-mail</span
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="phone">Teléfono</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="phone"
                        @input="$v.phone.$touch()"
                        value=""
                    />
                    <span
                        class="help-block"
                        v-if="$v.phone.$error && !$v.phone.required"
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="$v.phone.$error && !$v.phone.numeric"
                        >Solo números</span
                    >

                    <span
                        class="help-block"
                        v-if="$v.phone.$error && !$v.phone.maxLength"
                        >Longitud máxima de 20 números</span
                    >
                </div>

                <div class="col-md-4">
                    <label for="cell_phone">Celular</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model.trim="cell_phone"
                        @input="$v.cell_phone.$touch()"
                        value=""
                    />
                    <span
                        class="help-block"
                        v-if="$v.cell_phone.$error && !$v.cell_phone.required"
                        >Requerido</span
                    >
                    <span
                        class="help-block"
                        v-if="$v.cell_phone.$error && !$v.cell_phone.numeric"
                        >Solo números</span
                    >

                    <span
                        class="help-block"
                        v-if="$v.cell_phone.$error && !$v.cell_phone.maxLength"
                        >Longitud máxima de 20 números</span
                    >
                </div>

                <div class="col-md-4">
                    <label for="another_phone">Teléfono otro</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="another_phone"
                        value=""
                    />
                </div>

                <span
                    class="help-block"
                    v-if="$v.another_phone.$error && !$v.another_phone.numeric"
                    >Solo números</span
                >

                <span
                    class="help-block"
                    v-if="
                        $v.another_phone.$error && !$v.another_phone.maxLength
                    "
                    >Longitud máxima de 20 números</span
                >
            </div>

            <div class="row"><hr /></div>

            <div class="row">
                <div class="col-md-4">
                    <label for="type_procedure_id">Tipo de trámite</label>
                    <select
                        v-model="type_procedure_id"
                        @change="onChangeTypeProcedure()"
                        class="form-control form-control-sm dropdown-toggle"
                    >
                        <option
                            v-for="(item, index) in types_procedures"
                            v-bind:value="item.id"
                            :key="index"
                        >
                            {{ item.name }}
                        </option>
                    </select>
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
import { required, numeric, email, maxLength } from "vuelidate/lib/validators";
import moment from "moment";

// const axios = require('axios');

export default {
    components: {
        Datepicker
    },
    props: {
        types_documents: Array,
        types_procedures: Array
    },
    mixins: [validationMixin],
    validations: {
        document_number: { required, numeric, maxLength: maxLength(20) },
        first_name: { required },
        last_name: { required },
        gender: { required },
        address: { required },
        location: { required },
        email: { required, email },
        phone: { required, numeric, maxLength: maxLength(20) },
        cell_phone: { required, numeric, maxLength: maxLength(20) },
        another_phone: { numeric, maxLength: maxLength(20) },
        form: [
            "document_number",
            "first_name",
            "last_name",
            "gender",
            "address",
            "location",
            "email",
            "phone",
            "cell_phone",
            "another_phone"
        ]
    },
    data() {
        return {
            es: es,
            finalModel: {},
            document_type_id: "",
            document_number: "",
            person_id: 0,
            first_name: "",
            last_name: "",
            birth_date: new Date(),
            gender: "1",
            type_procedure_id: "",
            address: "",
            location: "",
            postal_code: "",
            phone: "",
            cell_phone: "",
            another_phone: "",
            email: "",
            hide_step_2: false
        };
    },
    methods: {
        findPerson() {
            if (this.document_number) {
                axios
                    .get(
                        `/persons/${this.document_type_id}/${this.document_number}`
                    )
                    .then(response => {
                        var item = response.data.data;
                        this.person_id = item.id;
                        this.first_name = item.first_name;
                        this.last_name = item.last_name;
                        this.gender = item.gender;
                        this.birth_date = moment(item.birth_date).toDate();
                        this.address = item.address;
                        this.location = item.location;
                        this.postal_code = item.postal_code;
                        this.email = item.email;
                        this.phone = item.phone;
                        this.cell_phone = item.cell_phone;
                        this.another_phone = item.another_phone;
                    })
                    .catch(error => {
                        this.person_id = 0;
                        this.first_name = "";
                        this.last_name = "";
                        this.gender = "1";
                        this.birth_date = new Date();
                        this.address = "";
                        this.location = "";
                        this.postal_code = "";
                        this.email = "";
                        this.phone = "";
                        this.cell_phone = "";
                        this.another_phone = "";
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
        onChangeTypeProcedure() {
            this.$emit("typeProcedureId", this.type_procedure_id);
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
    computed: {
        computedDateFormatted() {
            return this.formatDate(this.date);
        }
    },
    mounted() {
        this.gender = "1";
        this.document_type_id = this.types_documents[0].id;
        this.type_procedure_id = this.types_procedures[0].id;
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
