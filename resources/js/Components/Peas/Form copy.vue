<script setup>
import FormSection from "@/Components/FormSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInputCareer from "@/Components/SelectInputCpea.vue";
import SelectInputSubject from "@/Components/SelectInputSpea.vue";

import InputError from "@/Components/InputError.vue";
import { computed, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { reactive } from "vue";

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
    subjects: {
        type: Object,
        required: true,
    },
    teachers: {
        type: Object,
        required: true,
    },
    careersubjects: {
        type: Object,
        required: true,
    },
    academicperiods: {
        type: Object,
        required: true,
    },
    subjectRequisite: {
        type: Object,
        required: true,
    },
    subjectst: {
        type: Object,
        required: true,
    },
    specificcompetences: {
        type: Object,
        required: true,
    },
    genericcompetences: {
        type: Object,
        required: true,
    },
    teachinglearningstrategies: {
        type: Object,
        required: true,
    },
    curricularunits: {
        type: Object,
        required: true,
    },
    teachingcontents: {
        type: Object,
        required: true,
    },
    evaluations: {
        type: Object,
        required: true,
    },
    bibliographies: {
        type: Object,
        required: true,
    },
    vicerrectorado: {
        type: Object,
        required: true,
    },
});

const totalHours = computed(() => {
    if (!props.subjects || props.subjects.length === 0) {
        return 0;
    }
    let total = 0;
    props.subjects.forEach((subject) => {
        total +=
            Number(subject.contact_teacher || 0) +
            Number(subject.practical_teaching || 0) +
            Number(subject.self_employment || 0) +
            Number(subject.practical_self_employment || 0);
    });
    return total;
});

const getSubjectName = (subjectId) => {
    const subject = props.subjectst.find((s) => s.id === subjectId);
    return subject ? subject.subject_name : "Sin asignatura";
};
const getSubjectCode = (subjectId) => {
    const subject = props.subjectst.find((s) => s.id === subjectId);
    return subject ? subject.subject_code : "Sin asignatura";
};

const genericCompetencesString = computed(() => {
    if (props.genericcompetences && props.genericcompetences.length > 0) {
        return props.genericcompetences
            .map((competence) => competence.soft_skills)
            .join(", ");
    } else {
        return "";
    }
});

const totalContactTeacherHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        return total + (parseInt(content.contact_teacher_duration) || 0);
    }, 0);
});

const totalPracticalTeacherHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        return total + (parseInt(content.practical_teaching_duration) || 0);
    }, 0);
});

const totalSelfEmploymentHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        return total + (parseInt(content.self_employment_duration) || 0);
    }, 0);
});

const totalPracticalSelfEmploymentHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        return (
            total + (parseInt(content.practical_self_employment_duration) || 0)
        );
    }, 0);
});

const totalHours2 = computed(() => {
    return (
        totalContactTeacherHours.value +
        totalPracticalTeacherHours.value +
        totalSelfEmploymentHours.value +
        totalPracticalSelfEmploymentHours.value
    );
});

const totalPartial1 = computed(() => {
    return props.evaluations.reduce((total, evaluation) => {
        return total + (parseFloat(evaluation.first_partial) || 0);
    }, 0);
});

const totalPartial2 = computed(() => {
    return props.evaluations.reduce((total, evaluation) => {
        return total + (parseFloat(evaluation.second_partial) || 0);
    }, 0);
});

// Emitir evento de envío del formulario
//const emit = defineEmits(["submit"]);

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
        elf_employment: "",
        practical_self_employment: "",
        prerequisites: "",
        prerequisites_code: "",
        corequisites: "",
        corequisites_code: "",
        description_subject: "",
        objectives_subject: "",
        gral_learning_outcome: "",
        competence_specif_learningoutcomes: "",
        competence_specif_contribution_level: "",
        generic_competences: "",
        research_lines_career: "",
        description_integrative_project: "",
        teachinglearningstrategies: "",
        curricularunit_unit: "",
        curricularunit_name: "",
        curricularunit_learning_outcomes: "",
        teachingcontent_schedule: "",
        teachingcontent_content: "",
        teachingcontent_contact_teacher: "",
        teachingcontent_contact_teacher_duration: "",
        teachingcontent_practical_teaching: "",
        teachingcontent_practical_teaching_duration: "",
        teachingcontent_self_employment: "",
        teachingcontent_self_employment_duration: "",
        teachingcontent_practical_self_employment: "",
        teachingcontent_practical_self_employment_duration: "",
        totalContactTeacherHours: "",
        totalPracticalTeacherHours: "",
        totalSelfEmploymentHours: "",
        totalPracticalSelfEmploymentHours: "",
        totalHours2: "",
        evaluation_unit: "",
        evaluation_Learning_outcomes: "",
        evaluation_type: "",
        evaluation_criteria: "",
        evaluation_activity: "",
        evaluation_first_partial: "",
        evaluation_second_partial: "",
        totalPartial1: "",
        totalPartial2: "",
        bibliography_type: "",
        bibliography_author: "",
        bibliography_year: "",
        bibliography_title: "",
        bibliography_editorial: "",
        bibliography_format: "",
        bibliography_code: "",
        bibliography_link: "",
        bibliography_uplift: "",
        teacher_approval: "",
        coordinator_approval: "",
        vice_rector_approval: "",
    },
});

const subjectsS = ref(
    props.subjects.reduce((acc, subject) => {
        acc[subject.subject_code] = subject.subject_code;
        form.details.subject_code = Object.values(acc).join(", ");
        form.subject_id = subject.id;
        form.details.curricular_org_unit = subject.curricular_org_unit;
        return acc;
    }, {})
);
const careersubjectsS = ref(
    props.careersubjects.reduce((acc, careersubject) => {
        acc[careersubject.level] = careersubject.level;
        form.details.level = Object.values(acc).join(", ");
        return acc;
    }, {})
);
const acPeriod = ref(
    props.academicperiods.reduce((acc, academicperiod) => {
        acc[academicperiod.name] = academicperiod.name;
        form.details.academic_period = Object.values(acc).join(", ");
        return acc;
    }, {})
);

const submitForm = () => {
    Inertia.post(route("peas.store"), form, {
        onSuccess: () => {
            generateWord(); // Generar el documento de Word después de crear el PEA
        },
    });
};

const generateWord = async () => {
    try {
        const response = await Inertia.post(route("peas.generate.word"), {
            pea: form.details.details,
        });
        if (response.ok) {
            alert("Documento de Word generado exitosamente.");
        }
    } catch (error) {
        console.error("Error al generar el documento de Word:", error);
    }
};
</script>

<template>
    <!--    <pre>{{ JSON.stringify(vicerrectorado, null, 2) }}</pre>-->
    <FormSection @submitted="submitForm">
        <template #title>
            {{ updating ? "Actualizar Evaluación" : "Crear Nueva Evaluación" }}
        </template>

        <template #description>
            {{
                updating
                    ? "Actualizar La Evaluación Seleccionada"
                    : "Crear Una Nueva Evaluación Desde Cero"
            }}
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
                                    v-model="form.details.career"
                                    :options="careers"
                                    class="mt-1 block w-full"
                                    required
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
                                        v-model="form.details.subject"
                                        :options="subjects"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <InputError
                                        :message="$page.props.errors.subject"
                                        class="mt-2"
                                    />
                                </div>
                                <div class="mt-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Codigo de la Asignatura:
                                    </label>

                                    <input
                                        v-for="subject in subjects"
                                        :key="subject.subject_code"
                                        v-model="
                                            subjectsS[subject.subject_code]
                                        "
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
                                        v-for="careersubject in careersubjects"
                                        :key="careersubject.level"
                                        v-model="
                                            careersubjectsS[careersubject.level]
                                        "
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
                                        v-for="subject in subjects"
                                        :key="subject.curricular_org_unit"
                                        v-model="subject.curricular_org_unit"
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
                                        v-for="academicperiod in academicperiods"
                                        :key="academicperiod.name"
                                        v-model="acPeriod[academicperiod.name]"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div
                                    v-for="(value, key) in {
                                        modality: 'Modalidad:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <input
                                        v-for="career in careers"
                                        :key="career.madality"
                                        v-model="career.modality"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>

                                <div
                                    v-for="(value, key) in {
                                        teachers: 'Docente:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <input
                                        v-for="teacher in teachers"
                                        :key="teacher.name"
                                        v-model="teacher.name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div
                                    v-for="(value, key) in {
                                        email: 'Correo Docente:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <input
                                        v-for="teacher in teachers"
                                        :key="teacher.email"
                                        v-model="teacher.email"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div
                                    v-for="(value, key) in {
                                        credits: 'Créditos:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>
                                    <input
                                        v-for="subject in subjects"
                                        :key="subject.credits"
                                        v-model="subject.credits"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
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
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                        readonly
                                    />
                                </div>

                                <div>
                                    <div
                                        v-for="(value, key) in {
                                            contact_teacher:
                                                'Contacto Con El Docente:',
                                            practical_teaching:
                                                'Trabajo Práctico/ Experimental Con El Docente:',
                                            self_employment:
                                                'Trabajo Autónomo:',
                                            practical_self_employment:
                                                'Trabajo Práctico/ Experimental Autónomo:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <input
                                            v-for="subject in subjects"
                                            :key="subject[key]"
                                            v-model="subject[key]"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                            type="number"
                                        />
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                2. PRERREQUISITOS Y CORREQUISITOS
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-8 gap-1">
                                <div class="col-span-3">
                                    <div
                                        v-for="(value, key) in {
                                            prerequisites: 'Prerrequisitos:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <div
                                            v-for="subjectR in subjectRequisite"
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
                                    <div
                                        v-for="(value, key) in {
                                            subject_code: 'Codigo:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <div
                                            v-for="subjectR in subjectRequisite"
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
                                    <div
                                        v-for="(value, key) in {
                                            corequisites: 'Correquisitos:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <div
                                            v-for="subjectR in subjectRequisite"
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
                                    <div
                                        v-for="(value, key) in {
                                            subject_code: 'Codigo:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <div
                                            v-for="subjectR in subjectRequisite"
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
                            <div
                                v-for="(value, key) in {
                                    description:
                                        'Función específica de la asignatura en la formación profesional (descripción de la asignatura):',
                                }"
                                :key="key"
                            >
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ value }}
                                </label>

                                <textarea
                                    v-for="subject in subjects"
                                    :key="subject.description"
                                    v-model="subject.description"
                                    rows="6"
                                    class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div
                                v-for="(value, key) in {
                                    objectives: 'Objetivo de la asignatura:',
                                }"
                                :key="key"
                                class="mt-2"
                            >
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ value }}
                                </label>

                                <textarea
                                    v-for="subject in subjects"
                                    :key="subject.objectives"
                                    v-model="subject.objectives"
                                    rows="4"
                                    class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div
                                v-for="(value, key) in {
                                    learning_outcomes:
                                        'Resultado de aprendizaje general de la asignatura:',
                                }"
                                :key="key"
                                class="mt-2"
                            >
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ value }}
                                </label>

                                <textarea
                                    v-for="subject in subjects"
                                    :key="subject.gral_learning_outcome"
                                    v-model="subject.gral_learning_outcome"
                                    rows="5"
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
                                    <div
                                        v-for="(value, key) in {
                                            learningoutcomes:
                                                'Resultado de Aprendizaje:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <textarea
                                            v-for="specificcompetence in specificcompetences"
                                            :key="
                                                specificcompetence.Learning_outcomes
                                            "
                                            v-model="
                                                specificcompetence.Learning_outcomes
                                            "
                                            rows="2"
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div
                                        v-for="(value, key) in {
                                            contribution_level:
                                                'Nivel de contribución:',
                                        }"
                                        :key="key"
                                        class="text-right"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <textarea
                                            v-for="specificcompetence in specificcompetences"
                                            :key="
                                                specificcompetence.contribution_level
                                            "
                                            v-model="
                                                specificcompetence.contribution_level
                                            "
                                            rows="2"
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div
                                    v-for="(value, key) in {
                                        generic_competences:
                                            'Competencias Genéricas:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        :value="genericCompetencesString"
                                        rows="3"
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div
                                    v-for="(value, key) in {
                                        research_lines:
                                            'Lineas de investigacion de la carrera:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>
                                    <textarea
                                        v-for="career in careers"
                                        :key="career.research_lines"
                                        v-model="career.research_lines"
                                        rows="3"
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div
                                    v-for="(value, key) in {
                                        description:
                                            'Descripcion del proyecto integrador de saberes:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>
                                    <textarea
                                        v-for="subject in subjects"
                                        :key="
                                            subject.description_integrative_project
                                        "
                                        v-model="
                                            subject.description_integrative_project
                                        "
                                        rows="3"
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                5. ESTRATEGIAS METODOLOGÍAS DE ENSEÑANZA –
                                APRENDIZAJE
                            </h2>
                            <div class="w-full">
                                <div
                                    v-for="(value, key) in {
                                        strategies: 'Estrategias:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>
                                    <textarea
                                        v-for="teachinglearningstrategie in teachinglearningstrategies"
                                        :key="
                                            teachinglearningstrategie.description
                                        "
                                        v-model="
                                            teachinglearningstrategie.description
                                        "
                                        rows="3"
                                        class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>

                            <h2 class="text-xl font-semibold mt-6 mb-4">
                                6. CONTENIDOS DE ENSEÑANZA
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="row-span-2 col-span-1 w-full mt-10">
                                    <div
                                        v-for="(value, key) in {
                                            unit: 'Unidad:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <input
                                            v-for="curricularunit in curricularunits"
                                            :key="curricularunit.unit"
                                            v-model="curricularunit.unit"
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>
                                <div class="col-span-3 w-full">
                                    <div
                                        v-for="(value, key) in {
                                            name: 'Nombre de Unidad:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <input
                                            v-for="curricularunit in curricularunits"
                                            :key="curricularunit.name"
                                            v-model="curricularunit.name"
                                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm"
                                        />
                                    </div>
                                </div>
                                <div class="col-span-3 w-full">
                                    <div
                                        v-for="(value, key) in {
                                            lerningoutcome:
                                                'Resultado de Aprendizaje:',
                                        }"
                                        :key="key"
                                    >
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            {{ value }}
                                        </label>
                                        <input
                                            v-for="curricularunit in curricularunits"
                                            :key="
                                                curricularunit.learning_outcomes
                                            "
                                            v-model="
                                                curricularunit.learning_outcomes
                                            "
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
                                <div
                                    class="mt-7"
                                    v-for="(value, key) in {
                                        schedule: 'Semana /Fechas:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.schedule"
                                        v-model="teachingcontent.schedule"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-11"
                                    v-for="(value, key) in {
                                        content: 'contenido:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.content"
                                        v-model="teachingcontent.content"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    v-for="(value, key) in {
                                        learningAT:
                                            'Aprendizaje en contacto con el docente:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.contact_teacher"
                                        v-model="
                                            teachingcontent.contact_teacher
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-11"
                                    v-for="(value, key) in {
                                        duration: 'Horas:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="
                                            teachingcontent.contact_teacher_duration
                                        "
                                        v-model="
                                            teachingcontent.contact_teacher_duration
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-7"
                                    v-for="(value, key) in {
                                        LearningPET: 'Aprendizaje p/e docente:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="
                                            teachingcontent.practical_teaching
                                        "
                                        v-model="
                                            teachingcontent.practical_teaching
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-11"
                                    v-for="(value, key) in {
                                        duration: 'Horas:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="
                                            teachingcontent.practical_teaching_duration
                                        "
                                        v-model="
                                            teachingcontent.practical_teaching_duration
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-7"
                                    v-for="(value, key) in {
                                        LearningAu: 'Aprendizaje autónomo:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.self_employment"
                                        v-model="
                                            teachingcontent.self_employment
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-11"
                                    v-for="(value, key) in {
                                        duration: 'Horas:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="
                                            teachingcontent.self_employment_duration
                                        "
                                        v-model="
                                            teachingcontent.self_employment_duration
                                        "
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-3"
                                    v-for="(value, key) in {
                                        learningAuT:
                                            'Aprendizaje p/e autónomo:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
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
                                <div
                                    class="mt-11"
                                    v-for="(value, key) in {
                                        duration: 'Horas:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
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
                                <div
                                    class="mt-8"
                                    v-for="(value, key) in {
                                        unit: 'Unidad:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.unit"
                                        v-model="evaluation.unit"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-8 col-span-2"
                                    v-for="(value, key) in {
                                        learningoutcome:
                                            'Resultado de Aprendizaje:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.Learning_outcomes"
                                        v-model="evaluation.Learning_outcomes"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-8"
                                    v-for="(value, key) in {
                                        type: 'Tipo:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.type"
                                        v-model="evaluation.type"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-4 col-span-2"
                                    v-for="(value, key) in {
                                        criteria:
                                            'Criterios de evaluacion del aprendizaje:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.criteria"
                                        v-model="evaluation.criteria"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="col-span-2"
                                    v-for="(value, key) in {
                                        activity:
                                            'Actividades de evaluacion del aprendizaje (Formativa/ Sumativa):',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.activity"
                                        v-model="evaluation.activity"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-4"
                                    v-for="(value, key) in {
                                        partial1: 'Primer Parcial (10p):',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.first_partial"
                                        v-model="evaluation.first_partial"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-4"
                                    v-for="(value, key) in {
                                        partial2: 'Segundo Parcial (10p):',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
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
                                <div
                                    class="mt-10"
                                    v-for="(value, key) in {
                                        type: 'Tipo:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight font-semibold"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.type"
                                        v-model="bibliography.type"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-10"
                                    v-for="(value, key) in {
                                        content: 'Autor:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.author"
                                        v-model="bibliography.author"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-10"
                                    v-for="(value, key) in {
                                        learningAT: 'Año:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.year"
                                        v-model="bibliography.year"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-10"
                                    v-for="(value, key) in {
                                        duration: 'Titulo:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.title"
                                        v-model="bibliography.title"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-10"
                                    v-for="(value, key) in {
                                        LearningPET: 'Editorial:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.editorial"
                                        v-model="bibliography.editorial"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-10"
                                    v-for="(value, key) in {
                                        duration: 'Formato:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.format"
                                        v-model="bibliography.format"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-7"
                                    v-for="(value, key) in {
                                        LearningAu: 'Codigo del Libro:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.code"
                                        v-model="bibliography.code"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="mt-7"
                                    v-for="(value, key) in {
                                        duration: 'Link del recurso:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
                                        :key="bibliography.link"
                                        v-model="bibliography.link"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div
                                    class="col-span-2"
                                    v-for="(value, key) in {
                                        learningAuT:
                                            'Sustentación científica técnica y didáctico adecuado de la bibliografía básica.:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        {{ value }}
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
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
                                <div
                                    v-for="(value, key) in {
                                        teachers: 'Docente:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <input
                                        v-for="teacher in teachers"
                                        :key="teacher.name"
                                        v-model="teacher.name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div
                                    v-for="(value, key) in {
                                        coordinator: 'Coordinación de Carrera:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <input
                                        v-for="career in careers"
                                        :key="career.coordinator"
                                        v-model="career.coordinator"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    />
                                </div>
                                <div
                                    v-for="(value, key) in {
                                        viceuser: 'Vicerrectorado Académico:',
                                    }"
                                    :key="key"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ value }}
                                    </label>

                                    <input
                                        v-for="vice in vicerrectorado"
                                        :key="vice.name"
                                        v-model="vice.name"
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
            <PrimaryButton type="submit">
                {{ updating ? "Actualizar" : "Crear" }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
