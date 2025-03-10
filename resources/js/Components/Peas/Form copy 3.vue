<script>
export default {
    name: "PeaForm",
};
</script>
<script setup>
import FormSection from "@/Components/FormSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInputCareer from "@/Components/SelectInputCpea.vue";
//import SelectInputSubject from "@/Components/SelectInputSpea.vue";
import SelectInputSubject from "@/Components/SelectInputSubjectCode.vue";

import InputError from "@/Components/InputError.vue";
import { Inertia } from "@inertiajs/inertia";

import { defineEmits, computed, ref, reactive, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
    pea: {
        type: Object,
        required: false,
        default: () => ({}),
    },
    updating: {
        type: Boolean,
        required: false,
        default: false,
    },
    form: {
        type: Object,
    },
    careers: {
        type: Object,
        required: true,
    },
    subjectst: {
        type: Object,
        required: true,
    },
});
const form = reactive({
    subject_id: null,
    details: {
        career: "",
        subject: "",
        subject_code: "",
        level: "",
        curricular_org_unit: "",
        academic_period: "",
        modality: "",
        teacher: "",
        email: "",
        credits: "",
        totalHours: "",
        contact_teacher: "",
        practical_teaching: "",
        self_employment: "",
        practical_self_employment: "",
        prerequisites: [],
        prerequisites_code: [],
        corequisites: [],
        corequisites_code: [],
        description_subject: "",
        objectives_subject: "",
        gral_learning_outcome: "",
        competence_specif_learningoutcomes: [],
        competence_specif_contribution_level: [],
        generic_competences: "",
        research_lines_career: "",
        description_integrative_project: "",
        teachinglearningstrategies: "",
        curricularunit_unit: [],
        curricularunit_name: [],
        curricularunit_learning_outcomes: [],
        teachingcontent_schedule: [],
        teachingcontent_content: [],
        teachingcontent_contact_teacher: [],
        teachingcontent_contact_teacher_duration: [],
        teachingcontent_practical_teaching: [],
        teachingcontent_practical_teaching_duration: [],
        teachingcontent_self_employment: [],
        teachingcontent_self_employment_duration: [],
        teachingcontent_practical_self_employment: [],
        teachingcontent_practical_self_employment_duration: [],
        totalContactTeacherHours: "",
        totalPracticalTeacherHours: "",
        totalSelfEmploymentHours: "",
        totalPracticalSelfEmploymentHours: "",
        totalHours2: "",
        evaluation_unit: [],
        evaluation_Learning_outcomes: [],
        evaluation_type: [],
        evaluation_criteria: [],
        evaluation_activity: [],
        evaluation_first_partial: [],
        evaluation_second_partial: [],
        totalPartial1: "",
        totalPartial2: "",
        bibliography_type: [],
        bibliography_author: [],
        bibliography_year: [],
        bibliography_title: [],
        bibliography_editorial: [],
        bibliography_format: [],
        bibliography_code: [],
        bibliography_link: [],
        bibliography_uplift: [],
        teacher_approval: "",
        coordinator_approval: "",
        vice_rector_approval: "",
    },
});

const subjectsrel = ref([]);
const subjectdata = ref([]);
const careersubjectdata = ref([]);
const academicperioddata = ref([]);
const careerdata = ref([]);
const subjectrequisitesdata = ref([]);
const specificcompetencesdata = ref([]);
const genericcompetencesdata = ref([]);
const teachinglearningstrategiesdata = ref([]);
const curricularunitsdata = ref([]);
const teachingcontentsdata = ref([]);
const evaluationsdata = ref([]);
const bibliographiesdata = ref([]);
const teacherdata = ref([]);
const coordinatordata = ref([]);
const vicerectoratedata = ref([]);
const alldata = ref([]);
const loadSubjects = (id) => {
    // Recibe careerId como parámetro
    axios
        .get(`peas.show`, {
            // Cambia la URL
            params: {
                career_id: id, // Pasa careerId como parámetro
            },
        })
        .then((response) => {
            //subjects.value = response.data; // Asigna a subjects.value
            subjectsrel.value = response.data.subjects || {};
            careerdata.value = response.data.career || {};
            //console.error(response.data);
            //alert(response.data);
        })
        .catch((error) => {
            console.error("Error loading subjects:", error);
            //alert(error.data);
            alert(
                "No se pudieron cargar las asignaturas. Inténtelo de nuevo más tarde."
            );
        });
};
const loadall = (ids) => {
    axios
        .get(`peas.show`, {
            params: {
                subject_id: ids,
            },
        })
        .then((response) => {
            alldata.value = response.data;
            let errorMessages = "Faltan por completar los datos de:<br>";
            let hasErrors = false;

            subjectdata.value = response.data.subject || {};
            if (Object.keys(subjectdata.value).length === 0) {
                errorMessages += "* Asignatura<br>";
                hasErrors = true;
            }
            form.subject_id = ids;
            careersubjectdata.value = response.data.careersubject || {};
            if (Object.keys(careersubjectdata.value).length === 0) {
                errorMessages += "* Carrera-Asignatura<br>";
                hasErrors = true;
            }

            academicperioddata.value = response.data.academicperiods || {};
            if (Object.keys(academicperioddata.value).length === 0) {
                errorMessages += "* Periodo Académico<br>";
                hasErrors = true;
            }

            subjectrequisitesdata.value = response.data.subjectrequisites || [];
            if (subjectrequisitesdata.value.length === 0) {
                errorMessages += "* Requisitos de Asignatura<br>";
                hasErrors = true;
            }

            specificcompetencesdata.value =
                response.data.specificcompetences || [];
            if (specificcompetencesdata.value.length === 0) {
                errorMessages += "* Competencias Específicas<br>";
                hasErrors = true;
            }

            genericcompetencesdata.value =
                response.data.genericcompetences || [];
            if (genericcompetencesdata.value.length === 0) {
                errorMessages += "* Competencias Genéricas<br>";
                hasErrors = true;
            }

            teachinglearningstrategiesdata.value =
                response.data.teachinglearningstrategies || [];
            if (teachinglearningstrategiesdata.value.length === 0) {
                errorMessages += "* Estrategias de Enseñanza-Aprendizaje<br>";
                hasErrors = true;
            }

            curricularunitsdata.value = response.data.curricularunits || [];
            if (curricularunitsdata.value.length === 0) {
                errorMessages += "* Unidades Curriculares<br>";
                hasErrors = true;
            }

            teachingcontentsdata.value = response.data.teachingcontents || [];
            if (teachingcontentsdata.value.length === 0) {
                errorMessages += "* Contenidos de Enseñanza<br>";
                hasErrors = true;
            }

            evaluationsdata.value = response.data.evaluations || [];
            if (evaluationsdata.value.length === 0) {
                errorMessages += "* Evaluaciones<br>";
                hasErrors = true;
            }

            bibliographiesdata.value = response.data.bibliographies || [];
            if (bibliographiesdata.value.length === 0) {
                errorMessages += "* Bibliografías<br>";
                hasErrors = true;
            }

            teacherdata.value = response.data.teacher || {};
            if (Object.keys(teacherdata.value).length === 0) {
                errorMessages += "* Profesor<br>";
                hasErrors = true;
            }

            coordinatordata.value = response.data.coordinator || {};
            if (Object.keys(coordinatordata.value).length === 0) {
                errorMessages += "* Coordinador<br>";
                hasErrors = true;
            }

            vicerectoratedata.value = response.data.vicerectorate || {};
            if (Object.keys(vicerectoratedata.value).length === 0) {
                errorMessages += "* Vicerrectorado<br>";
                hasErrors = true;
            }

            if (hasErrors) {
                Swal.fire({
                    icon: "warning",
                    title: "Datos Incompletos",
                    html: `<div style="text-align: left;">${errorMessages}</div>`,
                    confirmButtonText: "Entendido",
                });
            }
        })
        .catch((error) => {
            console.error("Error loading subjects:", error);

            if (error.response) {
                // El servidor respondió con un código de estado fuera del rango 2xx
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text:
                        error.response.data.error ||
                        "Ocurrió un error al cargar los datos.",
                    confirmButtonText: "Entendido",
                });
            } else if (error.request) {
                // La solicitud se realizó pero no se recibió respuesta
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "No se pudo conectar con el servidor.",
                    confirmButtonText: "Entendido",
                });
            } else {
                // Algo sucedió al configurar la solicitud que desencadenó un error
                Swal.fire({
                    icon: "error",
                    title: "Error inesperado",
                    text: error.message,
                    confirmButtonText: "Entendido",
                });
            }
        });
};
const totalHours = computed(() => {
    if (
        subjectdata.value &&
        typeof subjectdata.value === "object" &&
        Object.keys(subjectdata.value).length > 0
    ) {
        const subject = subjectdata.value; // Obtiene el objeto directamente
        const total =
            Number(subject.contact_teacher || 0) +
            Number(subject.practical_teaching || 0) +
            Number(subject.self_employment || 0) +
            Number(subject.practical_self_employment || 0);

        //form.value.details.totalHours = total; // Actualiza form.details.totalHours

        return total;
    } else {
        // form.value.details.totalHours = 0; // Asegura que form.details.totalHours es 0 si no hay datos.
        return 0;
    }
});
const getSubjectName = (subjectId) => {
    //alert(subjectId);
    const subject = props.subjectst.find((s) => s.id === subjectId);

    return subject ? subject.subject_name : "Sin asignatura";
};
const getSubjectCode = (subjectId) => {
    const subject = props.subjectst.find((s) => s.id === subjectId);
    return subject ? subject.subject_code : "Sin asignatura";
};
const genericCompetencesString = computed(() => {
    if (
        Array.isArray(genericcompetencesdata.value) &&
        genericcompetencesdata.value.length > 0
    ) {
        return genericcompetencesdata.value
            .map((competence) => competence.soft_skills)
            .filter((skill) => skill) // Filtra valores falsy (undefined, null, "")
            .join(", ");
    } else {
        return "";
    }
});
const teachinglearningstrategiesString = computed(() => {
    if (
        Array.isArray(teachinglearningstrategiesdata.value) &&
        teachinglearningstrategiesdata.value.length > 0
    ) {
        return teachinglearningstrategiesdata.value
            .map(
                (teachinglearningstrategy) =>
                    teachinglearningstrategy.description
            )
            .filter((skill) => skill) // Filtra valores falsy (undefined, null, "")
            .join(", ");
    } else {
        return "";
    }
});

const totalContactTeacherHours = computed(() => {
    const total = teachingcontentsdata.value.reduce((acc, content) => {
        return acc + (parseInt(content.contact_teacher_duration) || 0);
    }, 0);
    //form.value.details.totalContactTeacherHours = total;
    return total;
});

const totalPracticalTeacherHours = computed(() => {
    const total = teachingcontentsdata.value.reduce((acc, content) => {
        return acc + (parseInt(content.practical_teaching_duration) || 0);
    }, 0);
    // form.value.details.totalPracticalTeacherHours = total;
    return total;
});

const totalSelfEmploymentHours = computed(() => {
    const total = teachingcontentsdata.value.reduce((acc, content) => {
        return acc + (parseInt(content.self_employment_duration) || 0);
    }, 0);
    // form.value.details.totalSelfEmploymentHours = total;
    return total;
});

const totalPracticalSelfEmploymentHours = computed(() => {
    const total = teachingcontentsdata.value.reduce((acc, content) => {
        return (
            acc + (parseInt(content.practical_self_employment_duration) || 0)
        );
    }, 0);
    //form.value.details.totalPracticalSelfEmploymentHours = total;
    return total;
});

const totalHours2 = computed(() => {
    const total =
        totalContactTeacherHours.value +
        totalPracticalTeacherHours.value +
        totalSelfEmploymentHours.value +
        totalPracticalSelfEmploymentHours.value;
    //form.value.details.totalHours2 = total;
    return total;
});

const totalPartial1 = computed(() => {
    const total = evaluationsdata.value.reduce((acc, evaluation) => {
        return acc + (parseFloat(evaluation.first_partial) || 0);
    }, 0);
    //form.value.details.totalPartial1 = total;
    return total;
});

const totalPartial2 = computed(() => {
    const total = evaluationsdata.value.reduce((acc, evaluation) => {
        return acc + (parseFloat(evaluation.second_partial) || 0);
    }, 0);
    //form.value.details.totalPartial2 = total;
    return total;
});

//const emit = defineEmits(["submit"]);
const submitForm = () => {
    Inertia.post(route("peas.store"), form);
    // emit("submit", form.subject_id); // Pasa form.value
};
</script>

<template>
    <pre>{{ JSON.stringify(form, null, 2) }}</pre>
    <!-- <FormSection @submitted="$emit('submit')">-->
    <FormSection @submitted="submitForm">
        <template #title>
            {{ updating ? "Actualizar PEA" : "Crear Nuevo PEA" }}
        </template>

        <template #description>
            {{ updating ? "Actualizar el PEA" : "Crear Un Nuevo PEA" }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <div>
                    <div>
                        <div>
                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                1. DATOS GENERALES
                            </h2>
                            <div class="mt-4">
                                <InputLabel for="career" value="Carrera" />
                                <SelectInputCareer
                                    id="career"
                                    v-model="form.career"
                                    :options="careers"
                                    class="mt-1 block w-full"
                                    required
                                    @change="loadSubjects(form.career)"
                                />
                                <InputError
                                    :message="$page.props.errors.career"
                                    class="mt-2"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mt-4">
                                    <InputLabel
                                        for="subject"
                                        value="Asignatura:"
                                    />
                                    <SelectInputSubject
                                        id="subject"
                                        v-model="form.subject"
                                        :options="subjectsrel"
                                        class="mt-1 block w-full"
                                        required
                                        @change="loadall(form.subject)"
                                    />
                                    <InputError
                                        :message="$page.props.errors.subject"
                                        class="mt-2"
                                    />
                                </div>
                                <div class="mt-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        for="subject_code"
                                    >
                                        Codigo de la Asignatura:
                                    </label>

                                    <input
                                        id="subject_code"
                                        name="subject_code"
                                        v-model="subjectdata.subject_code"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Nivel:
                                    </label>

                                    <input
                                        v-model="careersubjectdata.level"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Unidad de organización Curricular:
                                    </label>

                                    <input
                                        v-model="
                                            subjectdata.curricular_org_unit
                                        "
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Período Académico:
                                    </label>

                                    <input
                                        v-model="academicperioddata.name"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Modalidad:
                                    </label>

                                    <input
                                        v-model="careerdata.modality"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Docente:
                                    </label>

                                    <input
                                        v-model="teacherdata.name"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Correo Docente:
                                    </label>

                                    <input
                                        v-model="teacherdata.email"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Créditos:
                                    </label>
                                    <input
                                        v-model="subjectdata.credits"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                    <InputError
                                        :message="$page.props.errors.credits"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mt-10">
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        No. de Horas totales:
                                    </label>
                                    <input
                                        :value="totalHours"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                        readonly
                                    />
                                </div>

                                <div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Contacto Con El Docente:
                                        </label>
                                        <input
                                            v-model="
                                                subjectdata.contact_teacher
                                            "
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            type="number"
                                        />
                                        <InputError
                                            :message="
                                                $page.props.errors
                                                    .contact_teacher
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Trabajo Práctico/ Experimental Con
                                            El Docente:
                                        </label>
                                        <input
                                            v-model="
                                                subjectdata.practical_teaching
                                            "
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            type="number"
                                        />
                                        <InputError
                                            :message="
                                                $page.props.errors
                                                    .practical_teaching
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Trabajo Autónomo:
                                        </label>
                                        <input
                                            v-model="
                                                subjectdata.self_employment
                                            "
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            type="number"
                                        />
                                        <InputError
                                            :message="
                                                $page.props.errors
                                                    .self_employment
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Trabajo Práctico/ Experimental
                                            Autónomo:
                                        </label>
                                        <input
                                            v-model="
                                                subjectdata.practical_self_employment
                                            "
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            type="number"
                                        />
                                        <InputError
                                            :message="
                                                $page.props.errors
                                                    .practical_self_employment
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                2. PRERREQUISITOS Y CORREQUISITOS
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-8 gap-1">
                                <div class="col-span-3">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Prerrequisitos:
                                        </label>
                                        <div
                                            v-for="subjectR in subjectrequisitesdata"
                                            :key="subjectR.prerequisites"
                                        >
                                            <input
                                                :value="
                                                    getSubjectName(
                                                        subjectR.prerequisites
                                                    )
                                                "
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                                readonly
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Codigo:
                                        </label>
                                        <div
                                            v-for="subjectR in subjectrequisitesdata"
                                            :key="subjectR.prerequisites"
                                        >
                                            <div class="inline-block">
                                                <input
                                                    :value="
                                                        getSubjectCode(
                                                            subjectR.prerequisites
                                                        )
                                                    "
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-right"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Correquisitos:
                                        </label>
                                        <div
                                            v-for="subjectR in subjectrequisitesdata"
                                            :key="subjectR.corequisites"
                                        >
                                            <input
                                                :value="
                                                    getSubjectName(
                                                        subjectR.corequisites
                                                    )
                                                "
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                                readonly
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Codigo:
                                        </label>
                                        <div
                                            v-for="subjectR in subjectrequisitesdata"
                                            :key="subjectR.corequisites"
                                        >
                                            <div class="inline-block">
                                                <input
                                                    :value="
                                                        getSubjectCode(
                                                            subjectR.corequisites
                                                        )
                                                    "
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm text-right"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                3. INFORMACIÓN DE LA ASIGNATURA
                            </h2>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Función específica de la asignatura en la
                                    formación profesional (descripción de la
                                    asignatura):
                                </label>

                                <textarea
                                    v-model="subjectdata.description"
                                    rows="6"
                                    required
                                    class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div class="mt-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Objetivo de la asignatura:
                                </label>

                                <textarea
                                    v-model="subjectdata.objectives"
                                    rows="4"
                                    required
                                    class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div class="mt-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Resultado de aprendizaje general de la
                                    asignatura:
                                </label>

                                <textarea
                                    v-model="subjectdata.gral_learning_outcome"
                                    rows="5"
                                    required
                                    class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                4. MAPA DE COMPETENCIAS
                            </h2>
                            <h4 class="text-sm font-semibold mt-2 mb-2">
                                Competencias Específicas del perfil de egreso
                                que contribuyen a formar (resultados de
                                aprendizaje)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="col-span-3 w-full">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Resultado de Aprendizaje:
                                        </label>
                                        <textarea
                                            v-for="specificcompetence in specificcompetencesdata"
                                            :key="
                                                specificcompetence.Learning_outcomes
                                            "
                                            v-model="
                                                specificcompetence.Learning_outcomes
                                            "
                                            rows="2"
                                            required
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-right">
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Nivel de contribución:
                                        </label>
                                        <textarea
                                            v-for="specificcompetence in specificcompetencesdata"
                                            :key="
                                                specificcompetence.contribution_level
                                            "
                                            v-model="
                                                specificcompetence.contribution_level
                                            "
                                            rows="2"
                                            required
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Competencias Genéricas:
                                    </label>

                                    <textarea
                                        :value="genericCompetencesString"
                                        rows="3"
                                        required
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Lineas de investigacion de la carrera:
                                    </label>
                                    <textarea
                                        v-model="careerdata.research_lines"
                                        rows="3"
                                        required
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Descripcion del proyecto integrador de
                                        saberes:
                                    </label>
                                    <textarea
                                        v-model="
                                            subjectdata.description_integrative_project
                                        "
                                        rows="3"
                                        required
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                5. ESTRATEGIAS METODOLOGÍAS DE ENSEÑANZA
                                APRENDIZAJE
                            </h2>
                            <div class="w-full">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Estrategias:
                                    </label>
                                    <textarea
                                        :value="
                                            teachinglearningstrategiesString
                                        "
                                        rows="3"
                                        required
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                6. CONTENIDOS DE ENSEÑANZA
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="row-span-2 col-span-1 w-full mt-10">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Unidad:
                                        </label>
                                        <input
                                            v-for="curricularunit in curricularunitsdata"
                                            :key="curricularunit.unit"
                                            v-model="curricularunit.unit"
                                            required
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>
                                <div class="col-span-3 w-full">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Nombre de Unidad:
                                        </label>
                                        <input
                                            v-for="curricularunit in curricularunitsdata"
                                            :key="curricularunit.name"
                                            v-model="curricularunit.name"
                                            required
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>
                                <div class="col-span-3 w-full">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Resultado de Aprendizaje:
                                        </label>
                                        <input
                                            v-for="curricularunit in curricularunitsdata"
                                            :key="
                                                curricularunit.learning_outcomes
                                            "
                                            v-model="
                                                curricularunit.learning_outcomes
                                            "
                                            required
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                    <h2 class="text-sm font-semibold mt-6">
                                        METODOLOGÍAS DE ENSEÑANZA
                                        APRENDIZAJE/HORAS CLASE
                                    </h2>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-10 gap-0">
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Semana /Fechas:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="teachingcontent.schedule"
                                        v-model="teachingcontent.schedule"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        contenido:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="teachingcontent.content"
                                        v-model="teachingcontent.content"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje en contacto con el docente:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="teachingcontent.contact_teacher"
                                        v-model="
                                            teachingcontent.contact_teacher
                                        "
                                        required
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="
                                            teachingcontent.contact_teacher_duration
                                        "
                                        v-model="
                                            teachingcontent.contact_teacher_duration
                                        "
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje p/e docente:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="
                                            teachingcontent.practical_teaching
                                        "
                                        v-model="
                                            teachingcontent.practical_teaching
                                        "
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="
                                            teachingcontent.practical_teaching_duration
                                        "
                                        v-model="
                                            teachingcontent.practical_teaching_duration
                                        "
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje autónomo:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="teachingcontent.self_employment"
                                        v-model="
                                            teachingcontent.self_employment
                                        "
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="
                                            teachingcontent.self_employment_duration
                                        "
                                        v-model="
                                            teachingcontent.self_employment_duration
                                        "
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-3">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje p/e autónomo:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="
                                            teachingcontent.practical_self_employment
                                        "
                                        v-model="
                                            teachingcontent.practical_self_employment
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontentsdata"
                                        :key="
                                            teachingcontent.practical_self_employment_duration
                                        "
                                        v-model="
                                            teachingcontent.practical_self_employment_duration
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-10 gap-0">
                                <div class="col-span-3 font-semibold">
                                    Total
                                </div>
                                <div class="font-semibold text-center">
                                    {{ totalContactTeacherHours }}
                                </div>
                                <div></div>
                                <div class="font-semibold text-center">
                                    {{ totalPracticalTeacherHours }}
                                </div>
                                <div></div>
                                <div class="font-semibold text-center">
                                    {{ totalSelfEmploymentHours }}
                                </div>
                                <div></div>
                                <div class="font-semibold text-center">
                                    {{ totalPracticalSelfEmploymentHours }}
                                </div>
                            </div>
                            <h5 class="text-xs text-right">
                                Total de horas:
                                <span class="font-semibold">{{
                                    totalHours2
                                }}</span>
                            </h5>
                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                7. SISTEMA DE EVALUACIÓN
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-10 gap-0">
                                <div class="mt-8">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Unidad:
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.unit"
                                        v-model="evaluation.unit"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-8 col-span-2">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Resultado de Aprendizaje:
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.Learning_outcomes"
                                        v-model="evaluation.Learning_outcomes"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-8">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Tipo:
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.type"
                                        v-model="evaluation.type"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-4 col-span-2">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Criterios de evaluacion del aprendizaje:
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.criteria"
                                        v-model="evaluation.criteria"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Actividades de evaluacion del
                                        aprendizaje (Formativa/ Sumativa):
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.activity"
                                        v-model="evaluation.activity"
                                        rows="6"
                                        required
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-4">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Primer Parcial (10p):
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.first_partial"
                                        v-model="evaluation.first_partial"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-4">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Segundo Parcial (10p):
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluationsdata"
                                        :key="evaluation.second_partial"
                                        v-model="evaluation.second_partial"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-10 gap-0">
                                <div
                                    class="col-span-8 font-semibold text-center"
                                >
                                    Total
                                </div>
                                <div class="font-semibold text-center">
                                    {{ totalPartial1 }}
                                </div>
                                <div class="font-semibold text-center">
                                    {{ totalPartial2 }}
                                </div>
                            </div>
                            <h5 class="text-xs">
                                Para la aprobación de una asignatura se requiere
                                mínimo 7 puntos y una asistencia del 70%, la
                                nota final se obtiene promediando la nota de los
                                dos parciales.
                            </h5>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                8. BIBLIOGRAFÍA
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-10 gap-0">
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight font-semibold"
                                    >
                                        Tipo:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.type"
                                        v-model="bibliography.type"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Autor:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.author"
                                        v-model="bibliography.author"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Año:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.year"
                                        v-model="bibliography.year"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Titulo:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.title"
                                        v-model="bibliography.title"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Editorial:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.editorial"
                                        v-model="bibliography.editorial"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Formato:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.format"
                                        v-model="bibliography.format"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Codigo del Libro:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.code"
                                        v-model="bibliography.code"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Link del recurso:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.link"
                                        v-model="bibliography.link"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Sustentación científica técnica y
                                        didáctico adecuado de la bibliografía
                                        básica.:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographiesdata"
                                        :key="bibliography.uplift"
                                        v-model="bibliography.uplift"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                9. REVISIÓN Y APROBACIÓN
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Docente:
                                    </label>

                                    <input
                                        v-model="teacherdata.name"
                                        required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Coordinación de Carrera:
                                    </label>

                                    <input
                                        v-model="coordinatordata.name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Vicerrectorado Académico:
                                    </label>

                                    <input
                                        v-model="vicerectoratedata.name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
