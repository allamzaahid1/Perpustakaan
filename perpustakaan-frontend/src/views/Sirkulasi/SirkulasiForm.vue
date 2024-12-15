  <template>
    <div class="modal-overlay">
      <div class="modal-content">
        <h3>{{ selectedSirkulasi ? "Edit Sirkulasi" : "Tambah Sirkulasi" }}</h3>
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label>ID Buku</label>
            <input type="number" v-model="sirkulasi.id_buku" class="form-control" required />
          </div>
          <div class="form-group">
            <label>ID User</label>
            <input type="number" v-model="sirkulasi.id_user" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Tanggal Pinjam</label>
            <input type="date" v-model="sirkulasi.tgl_pinjam" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Tanggal Kembali</label>
            <input type="date" v-model="sirkulasi.tgl_kembali" class="form-control" required />
          </div>
          <div class="form-group">
            <label>Status</label>
            <select v-model="sirkulasi.status" class="form-control" required>
              <option value="dipinjam">dipinjam</option>
              <option value="kembali">kembali</option>
            </select>
          </div>
          <div class="form-group" v-if="sirkulasi.status === 'kembali'">
            <label>Tanggal Pengembalian</label>
            <input type="date" v-model="sirkulasi.tgl_pengembalian" class="form-control" required />
          </div>
          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
            <button
              type="button"
              class="btn btn-secondary btn-cancel"
              style="float: right;"
              @click="$emit('close')"
            >
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script>
  import axios from "axios";

  export default {
    name: "SirkulasiForm",
    props: ["selectedSirkulasi"],
    data() {
      return {
        sirkulasi: {
          id_buku: "",
          id_user: "",
          tgl_pinjam: "",
          tgl_kembali: "",
          tgl_pengembalian: "",
          status: "",
        },
      };
    },
    created() {
      if (this.selectedSirkulasi) {
        this.sirkulasi = { ...this.selectedSirkulasi };
      }
    },
    methods: {
      getAxiosInstance() {
        const token = localStorage.getItem("auth_token");
        return axios.create({
          baseURL: "http://localhost:8000/api",
          headers: { Authorization: `Bearer ${token}` },
        });
      },
      async submitForm() {
        try {
          // Hapus nilai tgl_pengembalian jika status 'dipinjam'
          if (this.sirkulasi.status === "dipinjam") {
            delete this.sirkulasi.tgl_pengembalian; // Menghapus field secara eksplisit
          }

          const axiosInstance = this.getAxiosInstance();

          if (this.selectedSirkulasi) {
            await axiosInstance.put(`/sirkulasi/${this.sirkulasi.id}`, this.sirkulasi);
            alert("Data berhasil diperbarui");
          } else {
            await axiosInstance.post("/sirkulasi", this.sirkulasi);
            alert("Data berhasil ditambahkan");
          }

          this.$emit("close");
        } catch (error) {
          console.error("Error:", error.response ? error.response.data : error.message);
          alert("Gagal menyimpan data.");
        }
      },
    },
  };
  </script>

    
  <style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background: #ffffff;
    padding: 30px;
    border-radius: 20px;
    width: 450px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  h3 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
  }
  
  input {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    transition: all 0.3s ease;
  }
  
  input:focus {
    border-color: #007bff;
    outline: none;
  }
  
  button {
    width: 48%;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn-submit {
    background-color: #4682b4;
    color: white;
    border: none;
  }
  
  .btn-submit:hover {
    background-color: #126b9b;
  }
  
  .btn-cancel {
    background-color: #f8d7da;
    color: #721c24;
    border: none;
  }
  
  .btn-cancel:hover {
    background-color: #f5c6cb;
  }
  </style>
  