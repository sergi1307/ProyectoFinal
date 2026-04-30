<script setup>
import { reactive, watch } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["close", "submit"]);
const toast = useToast();

const form = reactive({
  asunto: "",
  descripcion: "",
});

watch(
  () => props.isOpen,
  (newVal) => {
    if (newVal) {
      form.asunto = "";
      form.descripcion = "";
    }
  },
);

const closeModal = () => {
  emit("close");
};

const handleSubmit = async () => {
  const token = localStorage.getItem('token');
  
  try {
    const response = await axios.post('http://localhost:8080/api/incidents', {
      asunto: form.asunto,
      descripcion: form.descripcion
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json'
      }
    });

    if (response.status === 201 || response.status === 200) {
      toast.success("Incidencia enviada correctamente", {
        position: "top-center",
        timeout: 2000,
        hideProgressBar: false,
        closeButton: false,
      });

      emit("submit", { ...form });
      
      setTimeout(() => {
        closeModal();
      }, 1000);
    } else {
      console.log("Respuesta extraña del servidor:", response);
    }

  } catch (error) {
    console.error("Error real del servidor:", error.response?.data || error.message);
    toast.error("Error al conectar con el servidor", {
      position: "top-center"
    });
  }
};
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content" role="dialog" aria-modal="true">
          <div class="modal-header">
            <h2>
                <img src="../assets/logos/logo_bln.png" alt="Logo de Nexus">
                Enviar Incidencia de Soporte
            </h2>
            <button
              class="close-btn"
              @click="closeModal"
              aria-label="Cerrar modal"
            >
              &times;
            </button>
          </div>

          <form @submit.prevent="handleSubmit">
            <div class="modal-body">
              <div class="form-group">
                <label for="asunto">Asunto</label>
                <input
                  id="asunto"
                  v-model="form.asunto"
                  type="text"
                  placeholder="Describe brevemente el problema"
                  required
                />
              </div>

              <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea
                  id="descripcion"
                  v-model="form.descripcion"
                  rows="8"
                  no-resize
                  spellcheck="false"
                  placeholder="Describe el problema en detalle..."
                  required
                ></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn-cancel" @click="closeModal">
                Cancelar
              </button>
              <button type="submit" class="btn-submit">
                Enviar Incidencia
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>