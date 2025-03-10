<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Docxtemplater from "docxtemplater";
//import ImageModule from "docxtemplater-image-module";
import ImageModule from "docxtemplater-image-module-free";
import PizZip from "pizzip";
import { saveAs } from "file-saver";
import { computed, ref } from "vue";
import { format } from "date-fns";

const props = defineProps({
    peas: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const getAssetUrl = (path) => {
    // Verificar si path es nulo o indefinido
    if (!path) {
        path = "careers/sin_logo.png";
    }

    // Si window.asset ya devuelve una url absoluta usarla.
    if (path.startsWith("http://") || path.startsWith("https://")) {
        return path;
    }
    // Si window.asset devuelve una url relativa, construir la url absoluta.
    return window.location.origin + window.asset(path);
};

const search = ref("");
const sort = ref({
    column: null,
    order: "asc",
});

const deletePea = (id) => {
    if (confirm("¿Estás seguro?")) {
        Inertia.delete(route("peas.destroy", id));
    }
};

const generateWord = async (pea) => {
    try {
        const response = await fetch(route("peas.template.word"));
        const arrayBuffer = await response.arrayBuffer();

        const zip = new PizZip(arrayBuffer);

        // Configuración del módulo de imágenes
        const opts = {
            centered: true,
            fileType: "docx",
            getImage: async (tagValue, tagName) => {
                try {
                    console.log("Cargando imagen desde:", tagValue);

                    const imageResponse = await fetch(tagValue);

                    if (!imageResponse.ok) {
                        console.error(
                            `HTTP error! status: ${imageResponse.status}`
                        );
                        return null;
                    }

                    const arrayBuffer = await imageResponse.arrayBuffer();
                    return arrayBuffer;
                } catch (error) {
                    console.error("Error cargando la imagen:", error);
                    return null;
                }
            },
            getSize: (img, tagValue, tagName) => {
                return new Promise((resolve, reject) => {
                    const fixedHeightPixels = 100; // Altura fija en píxeles (puedes ajustar este valor)

                    const image = new Image();
                    image.onload = () => {
                        const aspectRatio = image.width / image.height;
                        const widthPixels = Math.round(
                            fixedHeightPixels * aspectRatio
                        );
                        resolve([widthPixels, fixedHeightPixels]);
                    };
                    image.onerror = (error) => {
                        console.error("Error al cargar la imagen:", error);
                        reject(error);
                    };
                    image.src = URL.createObjectURL(new Blob([img])); // img es un arrayBuffer
                });
            },
        };

        const imageModule = new ImageModule(opts);

        const doc = new Docxtemplater(zip, {
            paragraphLoop: true,
            linebreaks: true,
            modules: [imageModule],
        });

        // No necesitamos transformDetails, usamos pea.details directamente
        const details = pea.details;
        details.logo_path = getAssetUrl(pea.details.logo_path);

        console.log("pea.details:", details); // Imprime los detalles directamente
        const jsonString = JSON.stringify(details, null, 2);
        console.log(jsonString); // Imprime los detalles como JSON

        console.log("URL de la imagen insertada:", details.logo_path);

        // Renderiza el documento con los datos directamente
        try {
            doc.resolveData(details).then(() => {
                doc.render();

                const output = doc.getZip().generate({
                    type: "blob",
                    mimeType:
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                });

                saveAs(
                    output,
                    `PEA_${pea.details.subject}_${pea.details.subject_code}_${pea.details.level}_${pea.details.teacher}.docx`
                );
            });
        } catch (renderError) {
            console.error("Error al renderizar el documento:", renderError);
        }
    } catch (error) {
        console.error("Error generando el documento Word:", error);
    }
};

const generatePdf = (id) => {
    Inertia.post(route("peas.generate.pdf", id));
};

const changePage = (page) => {
    Inertia.get(route("peas.index", { page }));
};

const setSort = (column) => {
    if (sort.value.column === column) {
        sort.value.order = sort.value.order === "asc" ? "desc" : "asc";
    } else {
        sort.value.column = column;
        sort.value.order = "asc";
    }
};

const sortedPeas = computed(() => {
    let filtered = [...props.peas];

    if (search.value) {
        filtered = filtered.filter((pea) =>
            Object.values(pea).some((value) => {
                if (value && typeof value === "object") {
                    return Object.values(value).some((innerValue) =>
                        String(innerValue)
                            .toLowerCase()
                            .includes(search.value.toLowerCase())
                    );
                }
                return String(value)
                    .toLowerCase()
                    .includes(search.value.toLowerCase());
            })
        );
    }

    if (sort.value.column) {
        filtered.sort((a, b) => {
            const valA = String(a[sort.value.column]).toLowerCase();
            const valB = String(b[sort.value.column]).toLowerCase();

            if (valA < valB) {
                return sort.value.order === "asc" ? -1 : 1;
            }
            if (valA > valB) {
                return sort.value.order === "asc" ? 1 : -1;
            }
            return 0;
        });
    }

    const groups = {};
    filtered.forEach((pea) => {
        const teacherName = pea.details?.teacher || "Sin docente";
        if (!groups[teacherName]) {
            groups[teacherName] = [];
        }
        groups[teacherName].push(pea);
    });
    return groups;
});
</script>

<template>
    <AppLayout title="PEAs">
        <template #header>
            <h1>Plan de Estudios por Asignatura (PEA)</h1>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div
                        class="flex justify-between"
                        v-if="
                            $page.props.user.permissions.includes('crear pea')
                        "
                    >
                        <Link
                            :href="route('peas.create')"
                            class="rounded-md px-3 py-2 mx-1 sm:mx-2 text-sm sm:text-base text-white bg-[#013a6a] hover:bg-[#012a5a] transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#bf1627]"
                        >
                            Crear PEA
                        </Link>
                    </div>

                    <div class="mt-4">
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Buscar en todas las columnas..."
                            class="mb-4 w-full border rounded px-3 py-2"
                        />
                        <div
                            v-for="(peas, teacherName) in sortedPeas"
                            :key="teacherName"
                            class="mt-4"
                        >
                            <h2 class="text-lg font-semibold mb-2">
                                {{ teacherName }}
                            </h2>
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full table-auto border-collapse"
                                >
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                <button
                                                    @click="
                                                        setSort(
                                                            'subject.subject_name'
                                                        )
                                                    "
                                                >
                                                    Asignatura
                                                    {{
                                                        sort.column ===
                                                        "subject.subject_name"
                                                            ? sort.order ===
                                                              "asc"
                                                                ? "▲"
                                                                : "▼"
                                                            : ""
                                                    }}
                                                </button>
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                <button
                                                    @click="
                                                        setSort(
                                                            'details.career'
                                                        )
                                                    "
                                                >
                                                    Carrera
                                                    {{
                                                        sort.column ===
                                                        "details.career"
                                                            ? sort.order ===
                                                              "asc"
                                                                ? "▲"
                                                                : "▼"
                                                            : ""
                                                    }}
                                                </button>
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                <button
                                                    @click="
                                                        setSort('details.level')
                                                    "
                                                >
                                                    Nivel
                                                    {{
                                                        sort.column ===
                                                        "details.level"
                                                            ? sort.order ===
                                                              "asc"
                                                                ? "▲"
                                                                : "▼"
                                                            : ""
                                                    }}
                                                </button>
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700"
                                            >
                                                <button
                                                    @click="
                                                        setSort('created_at')
                                                    "
                                                >
                                                    Creado_en
                                                    {{
                                                        sort.column ===
                                                        "created_at"
                                                            ? sort.order ===
                                                              "asc"
                                                                ? "▲"
                                                                : "▼"
                                                            : ""
                                                    }}
                                                </button>
                                            </th>
                                            <th
                                                class="py-2 px-4 text-left text-sm font-semibold text-gray-700 text-right"
                                            >
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="pea in peas"
                                            :key="pea.id"
                                            class="border-b border-gray-200"
                                        >
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    pea.subject_id
                                                        ? pea.subject
                                                              .subject_name
                                                        : "Sin asignatura"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    pea.details.career ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    pea.details.level ||
                                                    "Sin datos"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-gray-800"
                                            >
                                                {{
                                                    pea.created_at
                                                        ? format(
                                                              new Date(
                                                                  pea.created_at
                                                              ),
                                                              "dd/MM/yyyy HH:mm:ss"
                                                          )
                                                        : "Sin fecha"
                                                }}
                                            </td>
                                            <td
                                                class="py-3 px-4 text-sm text-right"
                                            >
                                                <div
                                                    class="flex justify-end space-x-2"
                                                >
                                                    <Link
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                        :href="
                                                            route(
                                                                'peas.edit',
                                                                pea.id
                                                            )
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'no actualizar pea'
                                                            )
                                                        "
                                                    >
                                                        Editar
                                                    </Link>
                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            deletePea(pea.id)
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'eliminar pea'
                                                            )
                                                        "
                                                    >
                                                        Borrar
                                                    </button>
                                                    <button
                                                        class="flex flex-col items-center text-indigo-600 hover:text-indigo-900 group"
                                                        @click="
                                                            generateWord(pea)
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'leer pea'
                                                            )
                                                        "
                                                    >
                                                        <img
                                                            src="/images/icono_word.png"
                                                            alt="icono_word"
                                                            class="h-8 w-auto transition-transform duration-300 group-hover:scale-110"
                                                        />
                                                        <span
                                                            class="mt-1 text-center"
                                                            >Descargar</span
                                                        >
                                                    </button>

                                                    <button
                                                        class="text-red-600 hover:text-red-900"
                                                        @click="
                                                            generatePdf(pea.id)
                                                        "
                                                        v-if="
                                                            $page.props.user.permissions.includes(
                                                                'generar repostes peas'
                                                            )
                                                        "
                                                    >
                                                        Generar PDF
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <Link
                            v-if="pagination.current_page > 1"
                            @click="changePage(pagination.current_page - 1)"
                            class="py-2 px-4 text-white bg-indigo-500 hover:bg-indigo-700 rounded"
                        >
                            Anterior
                        </Link>
                        <div v-else></div>
                        <Link
                            v-if="
                                pagination.current_page < pagination.last_page
                            "
                            @click="changePage(pagination.current_page + 1)"
                            class="py-2 px-4 text-white bg-indigo-500 hover:bg-indigo-700 rounded"
                        >
                            Siguiente
                        </Link>
                        <div v-else></div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
