<script setup>
import { reactive, watch } from "vue";
import api from "@/services/api";
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
    const response = await api.post('/incidents', {
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
                  rows="5"
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

<style scoped>
h2 img {
  width: 150px;
  height: 60px;
  object-fit: cover;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(15, 23, 42, 0.85);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999;
}

.modal-content {
  background-color: #ffffff;
  width: 90%;
  max-width: 600px;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
  display: flex;
  flex-direction: column;
  height: auto;
  overflow: hidden;
  max-height: 90vh;
}

.modal-header {
  background-color: #0f172a;
  padding: 1.5rem 1.5rem 1rem;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-header h2 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #ffffff;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 0.2rem;
}

.close-btn {
  background: transparent;
  border: none;
  color: #ffffff;
  font-size: 1.5rem;
  cursor: pointer;
  line-height: 1;
  position: absolute;
  top: 1rem;
  right: 1.2rem;
}

form {
  display: flex;
  flex-direction: column;
}

.modal-body {
  padding: 1.2rem 1.5rem 0; 
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
  margin: 0 !important;
}

.form-group label {
  font-size: 0.9rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

input[type="text"],
select,
textarea {
  width: 100%;
  padding: 0.6rem 0.8rem;
  font-size: 0.9rem;
  color: #0f172a;
  background-color: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-family: inherit;
  box-sizing: border-box;
  margin: 0;
}

textarea {
  height: 85px;
  resize: none;
  display: block;
}

input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #0cc0df;
  background-color: #ffffff;
}

.select-wrapper { 
  position: relative; 
}

select { 
  appearance: none; 
  padding-right: 2rem; 
  cursor: pointer; 
}

.select-wrapper::after {
  content: '▼';
  font-size: 0.7rem;
  color: #0f172a;
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.modal-footer {
  background-color: transparent;
  padding: 1.2rem 1.5rem 1.8rem; 
  display: flex;
  gap: 1rem;
  border-top: none;
}

.btn-cancel, .btn-submit {
  border: none;
  padding: 0.6rem 1.2rem;
  font-size: 0.95rem;
  font-weight: bold;
  border-radius: 8px;
  cursor: pointer;
  margin: 0;
}

.btn-cancel {
  background-color: #e2e8f0;
  color: #0f172a;
  flex: 0 0 auto;
}

.btn-submit {
  background-color: #0cc0df;
  color: #0f172a;
  flex: 1;
}

.btn-submit:hover {
  background-color: #0aa5c0;
}

:global(.Vue-Toastification__container) {
  z-index: 100001 !important;
}
</style>v