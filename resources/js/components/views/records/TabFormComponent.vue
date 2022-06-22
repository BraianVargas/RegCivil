<template>
    <form-wizard
        color="#135d92"
        finishButtonText="Guardar"
        nextButtonText="Siguiente"
        backButtonText="Atras"
        stepSize="xs"
        title=""
        subtitle=""
        ref="wizard"
        @on-complete="onComplete"
    >
        <template slot="step" slot-scope="props">
            <wizard-step
                :tab="props.tab"
                :transition="props.transition"
                :key="props.tab.title"
                :index="props.index"
            >
            </wizard-step>
        </template>
        <tab-content
            icon="fa fa-user"
            title="Datos personales"
            index="0"
            :before-change="() => validateStep('step1')"
        >
            <step1
                ref="step1"
                @on-validate="mergePartialModels"
                v-on:typeProcedureId="onHideStep2"
                v-bind:types_documents="types_documents"
                v-bind:types_procedures="types_procedures"
            ></step1>
        </tab-content>
        <tab-content
            v-if="show_step"
            icon="fas fa-cog"
            title="Datos del trámite"
            index="1"
            :before-change="() => validateStep('step2')"
        >
            <step2
                ref="step2"
                @on-validate="mergePartialModels"
                v-bind:types_documents="types_documents"
                v-bind:types_proceedings="types_proceedings"
                v-bind:applicant="applicant"
                v-bind:offices="offices"
                v-bind:not_validate="hideStep2"
            ></step2>
        </tab-content>

        <tab-content
            v-if="show_step"
            icon="fa fa-users"
            title="Datos del retiro"
            index="2"
            :before-change="() => validateStep('step3')"
        >
            <step3
                ref="step3"
                @on-validate="mergePartialModels"
                v-bind:applicant="applicant"
                v-bind:types_documents="types_documents"
                v-bind:not_validate="hideStep2"
            ></step3>
        </tab-content>
        <tab-content
            icon="fa fa-calendar"
            title="Datos del turno"
            index="3"
            :before-change="() => validateStep('step4')"
        >
            <step4
                ref="step4"
                @on-validate="mergePartialModels"
                v-bind:delegations="delegations"
            ></step4>
        </tab-content>
        <tab-content icon="fa fa-calendar" title="Confirmación" index="4">
            <step5
                ref="step5"
                v-bind:types_documents="types_documents"
                v-bind:types_procedures="types_procedures"
                v-bind:types_proceedings="types_proceedings"
                v-bind:delegations="delegations"
                v-bind:offices="offices"
                v-bind:final_model="finalModel"
            ></step5>
        </tab-content>

        <template slot="footer" slot-scope="props">
            <div class="wizard-footer-right">
                <wizard-button
                    v-if="!props.isLastStep"
                    @click.native="props.nextTab()"
                    class="wizard-footer-right"
                    :style="props.fillButtonStyle"
                    >Siguiente</wizard-button
                >

                <wizard-button
                    v-else
                    :disabled="isDisabled"
                    class="wizard-footer-right finish-button"
                    :style="props.fillButtonStyle"
                    @click.native="onComplete"
                >
                    {{
                        props.isLastStep ? "Confirmar turno" : "Siguiente"
                    }}</wizard-button
                >
            </div>
        </template>
    </form-wizard>
</template>

<script>
//local registration
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import axios from "axios";

//component code
export default {
    components: {
        FormWizard,
        TabContent
    },
    props: {
        types_documents: Array,
        types_procedures: Array,
        types_proceedings: Array,
        offices: Array,
        delegations: Array
    },
    data() {
        return {
            isDisabled: false,
            show_step: true,
            hideStep2: false,
            finalModel: {},
            applicant: {
                person_id: 0,
                document_type_id: 1,
                document_number: "",
                first_name: "",
                last_name: ""
            }
        };
    },
    methods: {
        onComplete: function() {
            this.isDisabled = true;
            axios
                .post(`/turns/reserve`, this.finalModel)
                .then(response => {
                    toastr.success(
                        "Se ha asignado el turno correctamente!",
                        "Turno asignado"
                    );

                    setTimeout(function() {
                        window.location.href = "/records/register";
                        this.isDisabled = false;
                    }, 2000);
                })
                .catch(error => {
                    // Display a error toast, with a title
                    toastr.error(
                        "No se ha podido guardar la solicitud!",
                        "Error"
                    );
                    this.isDisabled = false;
                });
        },
        onHideStep2(value) {
            if (value == 2) {
                this.hideStep2 = true;
            } else {
                this.hideStep2 = false;
            }
        },
        gotToLastStep: function() {
            this.$refs.wizard.changeTab(0, 3);
        },
        validateStep(name) {
            var refToValidate = this.$refs[name];
            var result = refToValidate.validate();
            if (result && name == "step1") {
                if (this.hideStep2) {
                    this.show_step = false;
                    this.$refs.wizard.tabs[0].validationError = null;
                    this.$refs.wizard.changeTab(0, 2);
                }
            }
            return result;
        },
        mergePartialModels(model, isValid) {
            if (isValid) {
                // merging each step model into the final model
                this.finalModel = Object.assign({}, this.finalModel, model);

                this.applicant.person_id = this.finalModel.person_id;
                this.applicant.document_type_id = this.finalModel.document_type_id;
                this.applicant.document_number = this.finalModel.document_number;
                this.applicant.first_name = this.finalModel.first_name;
                this.applicant.last_name = this.finalModel.last_name;
                this.applicant.gender = this.finalModel.gender;
            }
        }
    },
    mounted() {
        this.show_step = true;
    }
};
</script>
<style lang="scss" >
.vue-form-wizard .wizard-header {
    padding: 0px !important;
    position: relative;
    border-radius: 3px 3px 0 0;
    text-align: center;
}

.vue-form-wizard .wizard-tab-content {
    min-height: 100px;
    padding: 10px 20px 10px !important;
}
</style>
