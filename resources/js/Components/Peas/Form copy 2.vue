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
import { computed, ref, reactive, onMounted } from "vue";
import axios from "axios";

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
//const emit = defineEmits(["submit"]);

const loadSubjects = () => {
    // Recibe careerId como parámetro
    axios
        .get(`/api/peas/subjects`, {
            // Cambia la URL
            params: {
                career_id: 2, // Pasa careerId como parámetro
            },
        })
        .then((response) => {
            // subjects.value = response.data; // Asigna a subjects.value
            alert("llegue aqui.");
        })
        .catch((error) => {
            console.error("Error loading subjects:", error);
            alert(2);
            alert(
                "No se pudieron cargar las asignaturas. Inténtelo de nuevo más tarde."
            );
        });
};

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
    form.details.totalHours = total;
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
        form.details.generic_competences = props.genericcompetences
            .map((competence) => competence.soft_skills)
            .join(", ");
        return props.genericcompetences
            .map((competence) => competence.soft_skills)
            .join(", ");
    } else {
        return "";
    }
});
const teachinglearningstrategiesString = computed(() => {
    if (
        props.teachinglearningstrategies &&
        props.teachinglearningstrategies.length > 0
    ) {
        form.details.teachinglearningstrategies =
            props.teachinglearningstrategies
                .map(
                    (teachinglearningstrategie) =>
                        teachinglearningstrategie.description
                )
                .join(", ");
        return props.teachinglearningstrategies
            .map(
                (teachinglearningstrategie) =>
                    teachinglearningstrategie.description
            )
            .join(", ");
    } else {
        return "";
    }
});

const totalContactTeacherHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        form.details.totalContactTeacherHours =
            total + (parseInt(content.contact_teacher_duration) || 0);

        return total + (parseInt(content.contact_teacher_duration) || 0);
    }, 0);
});

const totalPracticalTeacherHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        form.details.totalPracticalTeacherHours =
            total + (parseInt(content.practical_teaching_duration) || 0);

        return total + (parseInt(content.practical_teaching_duration) || 0);
    }, 0);
});

const totalSelfEmploymentHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        form.details.totalSelfEmploymentHours =
            total + (parseInt(content.self_employment_duration) || 0);

        return total + (parseInt(content.self_employment_duration) || 0);
    }, 0);
});

const totalPracticalSelfEmploymentHours = computed(() => {
    return props.teachingcontents.reduce((total, content) => {
        form.details.totalPracticalSelfEmploymentHours =
            total + (parseInt(content.practical_self_employment_duration) || 0);

        return (
            total + (parseInt(content.practical_self_employment_duration) || 0)
        );
    }, 0);
});

const totalHours2 = computed(() => {
    form.details.totalHours2 =
        totalContactTeacherHours.value +
        totalPracticalTeacherHours.value +
        totalSelfEmploymentHours.value +
        totalPracticalSelfEmploymentHours.value;
    return (
        totalContactTeacherHours.value +
        totalPracticalTeacherHours.value +
        totalSelfEmploymentHours.value +
        totalPracticalSelfEmploymentHours.value
    );
});

const totalPartial1 = computed(() => {
    return props.evaluations.reduce((total, evaluation) => {
        form.details.totalPartial1 =
            total + (parseFloat(evaluation.first_partial) || 0);

        return total + (parseFloat(evaluation.first_partial) || 0);
    }, 0);
});

const totalPartial2 = computed(() => {
    return props.evaluations.reduce((total, evaluation) => {
        form.details.totalPartial2 =
            total + (parseFloat(evaluation.second_partial) || 0);
        return total + (parseFloat(evaluation.second_partial) || 0);
    }, 0);
});

// Emitir evento de envío del formulario
//const emit = defineEmits(["submit"]);

const subjectsS = ref(
    props.subjects.reduce((acc, subject) => {
        acc[subject.subject_code] = subject.subject_code;
        form.details.subject_code = Object.values(acc).join(", ");
        form.subject_id = subject.id;
        form.details.curricular_org_unit = subject.curricular_org_unit;
        form.details.credits = subject.credits;
        //totalHours: "",
        form.details.contact_teacher = subject.contact_teacher;
        form.details.practical_teaching = subject.practical_teaching;
        form.details.self_employment = subject.self_employment;
        form.details.practical_self_employment =
            subject.practical_self_employment;
        form.details.description_subject = subject.description;
        form.details.objectives_subject = subject.objectives;
        form.details.gral_learning_outcome = subject.gral_learning_outcome;
        form.details.description_integrative_project =
            subject.description_integrative_project;

        return acc;
    }, {})
);
const careersS = ref(
    props.careers.reduce((acc, career) => {
        acc[career.modality] = career.modality;
        form.details.modality = Object.values(acc).join(", ");
        form.details.research_lines_career = career.research_lines;
        form.details.coordinator_approval = career.coordinator;
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
const teachersS = ref(
    props.teachers.reduce((acc, teacher) => {
        acc[teacher.name] = teacher.name;
        form.details.teacher = Object.values(acc).join(", ");
        form.details.email = teacher.email;
        form.details.teacher_approval = teacher.name;

        return acc;
    }, {})
);
/*const teachinglearningstrategiesS = ref(
    props.teachinglearningstrategies.reduce((acc, teachinglearningstrategy) => {
        acc[teachinglearningstrategy.description] =
            teachinglearningstrategy.description;
        form.details.teachinglearningstrategies = Object.values(acc).join(", ");
        return acc;
    }, {})
);*/
const vicerrectoradoS = ref(
    props.vicerrectorado.reduce((acc, vice) => {
        acc[vice.name] = vice.name;
        form.details.vice_rector_approval = Object.values(acc).join(", ");

        return acc;
    }, {})
);

onMounted(() => {
    props.subjectRequisite.forEach((subjectR) => {
        form.details.prerequisites.push(getSubjectName(subjectR.prerequisites));
        form.details.prerequisites_code.push(
            getSubjectCode(subjectR.prerequisites)
        );
        form.details.corequisites.push(getSubjectName(subjectR.corequisites));
        form.details.corequisites_code.push(
            getSubjectCode(subjectR.corequisites)
        );
    });
    props.specificcompetences.forEach((specificcompetence) => {
        form.details.competence_specif_learningoutcomes.push(
            specificcompetence.Learning_outcomes
        );
        form.details.competence_specif_contribution_level.push(
            specificcompetence.contribution_level
        );
    });
    props.curricularunits.forEach((curricularunit) => {
        form.details.curricularunit_unit.push(curricularunit.unit);
        form.details.curricularunit_name.push(curricularunit.name);
        form.details.curricularunit_learning_outcomes.push(
            curricularunit.learning_outcomes
        );
    });
    props.teachingcontents.forEach((teachingcontent) => {
        form.details.teachingcontent_schedule.push(teachingcontent.schedule);
        form.details.teachingcontent_content.push(teachingcontent.content);
        form.details.teachingcontent_contact_teacher.push(
            teachingcontent.contact_teacher
        );
        form.details.teachingcontent_contact_teacher_duration.push(
            teachingcontent.contact_teacher_duration
        );
        form.details.teachingcontent_practical_teaching.push(
            teachingcontent.practical_teaching
        );
        form.details.teachingcontent_practical_teaching_duration.push(
            teachingcontent.practical_teaching_duration
        );
        form.details.teachingcontent_self_employment.push(
            teachingcontent.self_employment
        );
        form.details.teachingcontent_self_employment_duration.push(
            teachingcontent.self_employment_duration
        );
        form.details.teachingcontent_practical_self_employment.push(
            teachingcontent.practical_self_employment
        );
        form.details.teachingcontent_practical_self_employment_duration.push(
            teachingcontent.practical_self_employment_duration
        );
    });
    props.evaluations.forEach((evaluation) => {
        form.details.evaluation_unit.push(evaluation.unit);
        form.details.evaluation_Learning_outcomes.push(
            evaluation.learning_outcomes
        );
        form.details.evaluation_type.push(evaluation.type);
        form.details.evaluation_criteria.push(evaluation.criteria);
        form.details.evaluation_activity.push(evaluation.activity);
        form.details.evaluation_first_partial.push(evaluation.first_partial);
        form.details.evaluation_second_partial.push(evaluation.second_partial);
    });
    props.bibliographies.forEach((bibliography) => {
        form.details.bibliography_type.push(bibliography.type);
        form.details.bibliography_author.push(bibliography.author);
        form.details.bibliography_year.push(bibliography.year);
        form.details.bibliography_title.push(bibliography.title);
        form.details.bibliography_editorial.push(bibliography.editorial);
        form.details.bibliography_format.push(bibliography.format);
        form.details.bibliography_code.push(bibliography.code);
        form.details.bibliography_link.push(bibliography.link);
        form.details.bibliography_uplift.push(bibliography.uplift);
    });
});

const submitForm = () => {
    Inertia.post(route("peas.store"), form, {
        onSuccess: () => {
            //  generateWord(); // Generar el documento de Word después de crear el PEA
        },
    });
};
</script>

<template>
    <!--    <pre>{{ JSON.stringify(vicerrectorado, null, 2) }}</pre>-->
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
                                    v-model="form.details.career"
                                    :options="careers"
                                    class="mt-1 block w-full"
                                    required
                                    @change="loadSubjects(form.details.career)"
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
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Modalidad:
                                    </label>

                                    <input
                                        v-for="career in careers"
                                        :key="career.madality"
                                        v-model="careersS[career.modality]"
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
                                        v-for="teacher in teachers"
                                        :key="teacher.name"
                                        v-model="teachersS[teacher.name]"
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
                                        v-for="teacher in teachers"
                                        :key="teacher.email"
                                        v-model="teacher.email"
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
                                        v-for="subject in subjects"
                                        :key="subject.credits"
                                        v-model="subject.credits"
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
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Prerrequisitos:
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
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Codigo:
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
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Correquisitos:
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
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Codigo:
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
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Función específica de la asignatura en la
                                    formación profesional (descripción de la
                                    asignatura):
                                </label>

                                <textarea
                                    v-for="subject in subjects"
                                    :key="subject.description"
                                    v-model="subject.description"
                                    rows="6"
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
                                    v-for="subject in subjects"
                                    :key="subject.objectives"
                                    v-model="subject.objectives"
                                    rows="4"
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
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Resultado de Aprendizaje:
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
                                    <div class="text-right">
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Nivel de contribución:
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
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Competencias Genéricas:
                                    </label>

                                    <textarea
                                        :value="genericCompetencesString"
                                        rows="3"
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
                                        v-for="career in careers"
                                        :key="career.research_lines"
                                        v-model="career.research_lines"
                                        rows="3"
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
                                            v-for="curricularunit in curricularunits"
                                            :key="curricularunit.unit"
                                            v-model="curricularunit.unit"
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
                                            v-for="curricularunit in curricularunits"
                                            :key="curricularunit.name"
                                            v-model="curricularunit.name"
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
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Semana /Fechas:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.schedule"
                                        v-model="teachingcontent.schedule"
                                        rows="6"
                                        class="mt-0 block w-full border border-gray-300 rounded-md shadow-sm text-xs p-0 m-0 leading-tight"
                                    />
                                </div>
                                <div class="mt-11" :key="key">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        contenido:
                                    </label>

                                    <textarea
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.content"
                                        v-model="teachingcontent.content"
                                        rows="6"
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
                                        v-for="teachingcontent in teachingcontents"
                                        :key="teachingcontent.contact_teacher"
                                        v-model="
                                            teachingcontent.contact_teacher
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
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje p/e docente:
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
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
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
                                <div class="mt-7">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje autónomo:
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
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
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
                                <div class="mt-3">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Aprendizaje p/e autónomo:
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
                                <div class="mt-11">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Horas:
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
                                <div class="mt-8">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight"
                                    >
                                        Unidad:
                                    </label>

                                    <textarea
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.unit"
                                        v-model="evaluation.unit"
                                        rows="6"
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
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.Learning_outcomes"
                                        v-model="evaluation.Learning_outcomes"
                                        rows="6"
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
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.type"
                                        v-model="evaluation.type"
                                        rows="6"
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
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.criteria"
                                        v-model="evaluation.criteria"
                                        rows="6"
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
                                        v-for="evaluation in evaluations"
                                        :key="evaluation.activity"
                                        v-model="evaluation.activity"
                                        rows="6"
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
                                        v-for="evaluation in evaluations"
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
                                <div class="mt-10">
                                    <label
                                        class="block text-xs font-medium text-gray-700 p-0 m-0 leading-tight font-semibold"
                                    >
                                        Tipo:
                                    </label>

                                    <textarea
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                        v-for="bibliography in bibliographies"
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
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Docente:
                                    </label>

                                    <input
                                        v-for="teacher in teachers"
                                        :key="teacher.name"
                                        v-model="teacher.name"
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
                                        v-for="career in careers"
                                        :key="career.coordinator"
                                        v-model="career.coordinator"
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
                                        v-for="vice in vicerrectorado"
                                        :key="vice.name"
                                        v-model="vicerrectoradoS[vice.name]"
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
