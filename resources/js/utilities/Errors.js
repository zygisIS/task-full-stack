class Errors {
	/**
     * Create a new Errors instance.
     */
	constructor() {
		this.errors = {};
	}

	/**
     * Retrieve the error message for a field.
     *
     * @param {string} field
     */
	get(field) {
		if (this.errors[field]) {
			return this.errors[field][0];
		}
	}

	/**
     * Record the new errors.
     *
     * @param {object} errors
     */
	record(errors) {
		this.errors = errors;
	}

	clear(field) {
		delete this.errors[field];
	}

	/**
     * Determine if an errors exists for the given field.
     *
     * @param {string} field
     */
	has(field) {
		if (field)
			return this.errors.hasOwnProperty(field);
	}

	/**
     * Determine if we have any errors.
     */
	any() {
		return Object.keys(this.errors).length > 0;
	}
	
}

export default Errors;