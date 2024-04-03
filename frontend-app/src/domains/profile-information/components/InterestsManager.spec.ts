import { VueWrapper, mount } from '@vue/test-utils';
import InterestsManager from './InterestsManager.vue';
import { expect, test } from 'vitest';
import i18n from '@/i18n';
import { type Ref } from 'vue';

test('Can add interest', () => {
    // Arrange
    const interest = 'Test interest';;

    const component: VueWrapper = mount(InterestsManager, {
        global: {
            plugins: [i18n],
        },
        props: {
            modelValue: [],
            'onUpdate:modelValue': (e: Ref<string[]>) => component.setProps({ modelValue: e.value }),
        },
    });
    expect(component.findAll('[data-test="interest"]')).toHaveLength(0);

    const inputField = component.find('[data-test="input-field"]');
    expect(inputField).not.toBeNull();

    const form = component.find('[data-test="form"]');
    expect(form).not.toBeNull();

    // Act
    inputField.setValue(interest);
    form.trigger('submit');

    // Assert
    const updateModelValueEvent = component.emitted<Array<Ref<string>>>('update:modelValue');
    expect(updateModelValueEvent).not.toBeUndefined();

    // This check is done so typescript linting isn't going to error on it
    if (updateModelValueEvent !== undefined) {
        expect(updateModelValueEvent).toHaveLength(1);
        expect(updateModelValueEvent[0][0].value).toEqual([interest]);
    }

    // Wait for vue to update the prop value
    setTimeout(() => {
        const interests = component.findAll('[data-test="interest"]');

        expect(interests).toHaveLength(1);
        expect(interests[0].element.textContent).toBe(interest);
    }, 1);
});
