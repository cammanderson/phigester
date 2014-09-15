<?php
namespace Phigester;

/**
 * \Phigester\RuleSetInterface
 *
 * Public interface defining a shorthand means of configuring a complete
 * set of related Rule definitions in one operation. To use an instance
 * of a class that implements this interface:
 * <ul>
 * <li>Create a concrete implementation of this interface.</li>
 * <li>As you are configuring your Digester instance, call digester->addRuleSet()
 * and pass the RuleSet instance.</li>
 * <li>Digester will call the addRuleInstances() method of your RuleSet
 * to configure the necessary rules.</li>
 * </ul>
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
interface RuleSetInterface
{
    /**
     * Add the set of Rule instances defined in this RuleSet to the specified
     * Digester instance.
     *
     * This method should only be called by a Digester instance.
     * @param \Phigester\Digester $digester Digester intance to which the new Rule
     *                                      instances should be added.
     */
    public function addRuleInstances(\Phigester\Digester $digester);
}
