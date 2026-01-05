import axios from 'axios';
import { reactive, ref } from 'vue';

export function useDynamicForm(templateId, jobId = null) {
    const formSchema = ref(null);
    const formData = reactive({});
    const errors = reactive({});
    const isLoading = ref(false);

    /**
     * Load form schema from server
     */
    const loadSchema = async () => {
        isLoading.value = true;
        try {
            const url = jobId
                ? `/api/templates/${templateId}/schema?job_id=${jobId}`
                : `/api/templates/${templateId}/schema`;

            const response = await axios.get(url);
            formSchema.value = response.data;

            // Initialize form data with default values
            if (response.data.sections) {
                response.data.sections.forEach((section) => {
                    section.fields.forEach((field) => {
                        if (field.value !== undefined) {
                            formData[field.code] = field.value;
                        } else if (field.default_value !== undefined) {
                            formData[field.code] = field.default_value;
                        }
                    });
                });
            }
        } catch (error) {
            console.error('Error loading form schema:', error);
        } finally {
            isLoading.value = false;
        }
    };

    /**
     * Validate form data against server
     */
    const validate = async () => {
        try {
            const response = await axios.post(
                `/api/templates/${templateId}/validate`,
                {
                    field_data: formData,
                },
            );

            if (response.data.valid) {
                Object.keys(errors).forEach((key) => delete errors[key]);
                return true;
            } else {
                Object.assign(errors, response.data.errors);
                return false;
            }
        } catch (error) {
            console.error('Validation error:', error);
            return false;
        }
    };

    /**
     * Set field value
     */
    const setFieldValue = (fieldCode, value) => {
        formData[fieldCode] = value;
        if (errors[fieldCode]) {
            delete errors[fieldCode];
        }
    };

    /**
     * Get field value
     */
    const getFieldValue = (fieldCode) => {
        return formData[fieldCode];
    };

    /**
     * Reset form
     */
    const reset = () => {
        Object.keys(formData).forEach((key) => delete formData[key]);
        Object.keys(errors).forEach((key) => delete errors[key]);
    };

    return {
        formSchema,
        formData,
        errors,
        isLoading,
        loadSchema,
        validate,
        setFieldValue,
        getFieldValue,
        reset,
    };
}
