import axios from 'axios';
import { ref } from 'vue';

export function useWorkflow(jobId) {
    const availableTransitions = ref([]);
    const fieldRules = ref({
        visibilityRules: {},
        requirementRules: [],
    });
    const isLoading = ref(false);

    /**
     * Load available transitions for current job status
     */
    const loadAvailableTransitions = async () => {
        isLoading.value = true;
        try {
            const response = await axios.get(
                `/api/jobs/${jobId}/available-transitions`,
            );
            availableTransitions.value = response.data.transitions || [];
        } catch (error) {
            console.error('Error loading transitions:', error);
        } finally {
            isLoading.value = false;
        }
    };

    /**
     * Load field visibility and requirement rules for current status
     */
    const loadFieldRules = async () => {
        try {
            const response = await axios.get(`/api/jobs/${jobId}/field-rules`);
            fieldRules.value = {
                visibilityRules: response.data.visibilityRules || {},
                requirementRules: response.data.requirementRules || [],
            };
        } catch (error) {
            console.error('Error loading field rules:', error);
        }
    };

    /**
     * Execute a workflow transition
     */
    const executeTransition = async (transitionId, data = {}) => {
        try {
            const response = await axios.post(
                `/jobs/${jobId}/transitions/${transitionId}`,
                data,
            );
            return response.data;
        } catch (error) {
            console.error('Error executing transition:', error);
            throw error;
        }
    };

    /**
     * Check if a specific transition is available
     */
    const canTransition = (transitionId) => {
        return availableTransitions.value.some((t) => t.id === transitionId);
    };

    /**
     * Get transition by ID
     */
    const getTransition = (transitionId) => {
        return availableTransitions.value.find((t) => t.id === transitionId);
    };

    return {
        availableTransitions,
        fieldRules,
        isLoading,
        loadAvailableTransitions,
        loadFieldRules,
        executeTransition,
        canTransition,
        getTransition,
    };
}
