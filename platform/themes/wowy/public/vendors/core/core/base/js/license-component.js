// This is the core Vue component definition
const LicenseFormComponent = {
    // These are the inputs the component expects (like HTML attributes)
    props: {
        id: {
            type: String,
            default: () => null,
            required: true
        },
        verifyUrl: {
            type: String,
            default: () => null,
            required: true
        },
        activateLicenseUrl: {
            type: String,
            default: () => null,
            required: true
        },
        deactivateLicenseUrl: {
            type: String,
            default: () => null,
            required: true
        },
        resetLicenseUrl: {
            type: String,
            default: () => null,
            required: true
        }
    },
    // This is the component's internal state
    data() {
        return {
            initialized: null,
            loading: true,
            verified: false,
            license: null
        };
    },
    // This function runs automatically when the component is added to the page
    mounted() {
        this.verifyLicense();
    },
    // These are the functions the component can perform
    methods: {
        // Checks the license status with the server
        async verifyLicense() {
            try {
                // Send a GET request to the verify URL
                const response = await $httpClient.makeWithoutErrorHandler().get(this.verifyUrl);
                this.verified = true;
                this.license = response.data.data;
            } catch (error) {
                // If the server sends a 400 error, show it to the user
                if (error.response && error.response.status === 400) {
                    Botble.showError(error.response.data.message);
                }
            } finally {
                // Whether it succeeded or failed, stop loading
                this.initialized = true;
                this.loading = false;
            }
        },

        // Runs when the user submits the form
        async onSubmit() {
            const formData = new FormData(this.$refs.formRef);
            await this.doActivateLicense(formData);
        },

        // Resets the license
        async resetLicense() {
            const formData = new FormData(this.$refs.formRef);
            await this.doResetLicense(formData);
        },

        // Deactivates the license
        async deactivateLicense() {
            this.loading = true;
            try {
                await $httpClient.make().post(this.deactivateLicenseUrl);
                this.verified = false; // License is no longer active
            } catch (error) {
                // Handle errors (original code didn't show specific handling here)
            } finally {
                this.loading = false;
            }
        },

        // Helper function that sends the activation request
        async doActivateLicense(formData) {
            this.loading = true;
            try {
                // Send a POST request with the form data
                const response = await $httpClient.make().postForm(this.activateLicenseUrl, formData);
                const r = response.data;

                // Update state and show success message
                this.verified = true;
                this.license = r.data;
                Botble.showSuccess(r.message);
            } catch (error) {
                // Handle errors
            } finally {
                this.loading = false;
            }
        },

        // Helper function that sends the reset request
        async doResetLicense(formData) {
            this.loading = true;
            try {
                const response = await $httpClient.make().postForm(this.resetLicenseUrl, formData);
                const r = response.data;

                // Update state and show success message
                this.verified = false;
                Botble.showSuccess(r.message);
            } catch (error) {
                // Handle errors
            } finally {
                this.loading = false;
            }
        }
    }
};

// This part registers the component with the main Vue application
// so it can be used in HTML as <v-license-form>
if (typeof vueApp !== 'undefined') {
    vueApp.booting(vueAppInstance => {
        vueAppInstance.component("v-license-form", LicenseFormComponent);
    });
}